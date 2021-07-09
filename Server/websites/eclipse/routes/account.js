var router = require("express").Router()

const path = require("path")

const session = require(path.join(global.rboxlo.root, "lib", "session"))
const user = require(path.join(global.rboxlo.root, "lib", "user"))

/**
 * Handles an account/create submission
 * 
 * @param {array} req Request body
 * @param {array} res Result body
 */
async function createAccount (req, res) {
    if (!global.rboxlo.env.PRIVATE_REGISTRATION) {
        return res.sendStatus(403)
    }

    let form = {
        username:  { invalid: false },
        email:     { invalid: false },
        password1: { invalid: false },
        password2: { invalid: false }
    }

    if (global.rboxlo.env.GOOGLE_RECAPTCHA_ENABLED) {
        if (!form.hasOwnProperty("captcha") && (!req.body.hasOwnProperty("g-recaptcha-response") || req.body["g-recaptcha-response"].length == 0)) {
            form.captcha = { invalid: true, message: "Please solve the captcha challenge." }
        }

        if (!form.hasOwnProperty("captcha")) {
            if (!await user.verifyCaptcha(req.rboxlo.ip, req.body["g-recaptcha-response"])) {
                form.captcha = { invalid: true, message: "You failed to solve the captcha challenge. Please try again." }
            }
        }

        if (form.hasOwnProperty("captcha")) {
            form = { captcha: form.captcha }
            return res.render("account/register", { title: "Register", form: form })
        }
    }

    user.createAccount(req.body, req.rboxlo.ip).then(async (response) => {
        if (response.success === true) {
            let result = await user.getNecessarySessionInfoForUser(response.userId)
            req.session.rboxlo.user = result
            res.redirect("/my/dashboard")

            return
        }

        // Failed
        for (const [target, value] of Object.entries(response.targets)) {
            if (target == "csrf") {
                continue
            }

            form[target].invalid = true
            form[target].message = value
        }

        res.render("account/register", { title: "Register", form: form })
    })
}

/**
 * Handles an account/login submission
 * 
 * @param {array} req Request body
 * @param {array} res Result body
 */
async function authenticate (req, res) {
    let challenge = await user.needsAuthenticationChallenge(req.rboxlo.ip)

    let form = {
        username: { invalid: false },
        password: { invalid: false },
    }
    
    if (global.rboxlo.env.GOOGLE_RECAPTCHA_ENABLED) {
        if (await user.needsAuthenticationChallenge(req.rboxlo.ip)) {
            if (!form.hasOwnProperty("captcha") && (!req.body.hasOwnProperty("g-recaptcha-response") || req.body["g-recaptcha-response"].length == 0)) {
                form.captcha = { invalid: true, message: "Please solve the captcha challenge." }
            }

            if (!form.hasOwnProperty("captcha")) {
                if (!user.verifyCaptcha(req.body["g-recaptcha-response"])) {
                    form.captcha = { invalid: true, message: "You failed to solve the captcha challenge. Please try again." }
                }
            }

            if (form.hasOwnProperty("captcha")) {
                form = { captcha: form.captcha }
                return res.render("account/login", { title: "Login", form: form, challenge: challenge })
            }
        }
    }
    
    var rememberMe = (req.body.hasOwnProperty("remember_me") && (req.body.remember_me === "true"))

    user.authenticate(req.body, req.rboxlo.ip, req.headers["user-agent"], 3, rememberMe).then(async (response) => {
        if (response.success === true) {
            if (rememberMe) {
                res.cookie("remember_me", user.formatLongTermSession(response.longTermSession), {
                    maxAge: (response.longTermSession.expires * 1000),
                    httpOnly: true
                })
            }

            req.session.rboxlo.user = await user.getNecessarySessionInfoForUser(response.userId)

            if (req.session.rboxlo.hasOwnProperty("redirect")) {
                delete req.session.rboxlo.redirect
                return res.redirect(req.session.rboxlo.redirect)
            } else {
                return res.redirect("/my/dashboard")
            }
        }

        // Failed
        for (const [target, value] of Object.entries(response.targets)) {
            form[target].invalid = true
            form[target].message = value
        }

        if (req.session.rboxlo.hasOwnProperty("redirect")) {
            delete req.session.rboxlo.redirect
        }

        if (response.targets.hasOwnProperty("username") && response.targets.username == "Invalid username or password.") {
            delete form.password
        }
        
        res.render("account/login", { title: "Login", form: form, challenge: challenge })
    })
}

router.post("/register", user.loggedOut, createAccount)
router.post("/login", user.loggedOut, authenticate)

router.get("/register", user.loggedOut, (req, res) => {
    if (!global.rboxlo.env.PRIVATE_REGISTRATION) {
        return res.sendStatus(403)
    }
    
    res.render("account/register", { title: "Register" })
})

router.get("/login", user.loggedOut, async (req, res) => {
    let challenge = await user.needsAuthenticationChallenge(req.rboxlo.ip)
    res.render("account/login", { title: "Login", challenge: challenge })
})

router.get("/logout", (req, res) => {
    if (req.cookies.hasOwnProperty("remember_me")) {
        res.clearCookie("remember_me")
    }
    
    session.clear(req)
    res.redirect("/account/login")
})

module.exports = router