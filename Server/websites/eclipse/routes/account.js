var router = require("express").Router()

const path = require("path")

const user = require(path.join(global.rboxlo.root, "websites", "eclipse", "lib", "user"))

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

    let objects = {
        "form": {
            "username": { invalid: false },
            "email": { invalid: false },
            "password1": { invalid: false },
            "password2": { invalid: false }
        },
        "csrf": req.csrfToken()
    }

    if (global.rboxlo.env.GOOGLE_RECAPTCHA_ENABLED) {
        if (!objects.form.hasOwnProperty("captcha") && (!req.body.hasOwnProperty("g-recaptcha-response") || req.body["g-recaptcha-response"].length == 0)) {
            objects.form.captcha = { invalid: true, message: "Please solve the captcha challenge." }
        }

        if (!objects.form.hasOwnProperty("captcha")) {
            if (!(await user.verifyCaptcha(req.rboxlo.ip, req.body["g-recaptcha-response"]))) {
                objects.form.captcha = { invalid: true, message: "You failed to solve the captcha challenge. Please try again." }
            }
        }

        if (objects.form.hasOwnProperty("captcha")) {
            objects.form = {
                "captcha": objects.form.captcha
            }

            return res.render("account/register", { "title": "Register", "objects": objects })
        }
    }

    user.createAccount(req.body, req.rboxlo.ip).then(async (response) => {
        if (response.success === true) {
            let result = await user.getNecessarySessionInfoForUser(response.userId)
            req.session.rboxlo.user = result
            res.redirect("/my/dashboard")
            return
        }

        for (const [target, value] of Object.entries(response.targets)) {
            if (target == "csrf") {
                continue
            }

            objects.form[target].invalid = true
            objects.form[target].message = value
        }

        res.render("account/register", { "title": "Register", "objects": objects })
    })
}

/**
 * Handles an account/login submission
 * 
 * @param {array} req Request body
 * @param {array} res Result body
 */
async function authenticate (req, res) {
    let challenge = (await user.needsAuthenticationChallenge(req.rboxlo.ip))

    let objects = {
        "form": {
            "username": { invalid: false },
            "password": { invalid: false },
        },
        "csrf": req.csrfToken(),
        "challenge": challenge
    }
    
    if (global.rboxlo.env.GOOGLE_RECAPTCHA_ENABLED) {
        if (await user.needsAuthenticationChallenge(req.rboxlo.ip)) {
            if (!objects.form.hasOwnProperty("captcha") && (!req.body.hasOwnProperty("g-recaptcha-response") || req.body["g-recaptcha-response"].length == 0)) {
                objects.form.captcha = { invalid: true, message: "Please solve the captcha challenge." }
            }

            if (!objects.form.hasOwnProperty("captcha")) {
                if (!user.verifyCaptcha(req.body["g-recaptcha-response"])) {
                    objects.form.captcha = { invalid: true, message: "You failed to solve the captcha challenge. Please try again." }
                }
            }

            if (objects.form.hasOwnProperty("captcha")) {
                objects.form = {
                    captcha: objects.form.captcha,
                    csrf: objects.form.csrf
                }

                return res.render("account/login", { "title": "Login", "objects": objects })
            }
        }
    }
    
    var rememberMe = (req.body.hasOwnProperty("rememberMe") && (req.body.rememberMe === "true"))

    user.authenticate(req.body, req.rboxlo.ip, req.headers["user-agent"], 3, rememberMe).then(async (response) => {
        if (response.success === true) {
            if (rememberMe) {
                res.cookie("remember_me", user.formatLongTermSession(response.longTermSession), {
                    maxAge: (response.longTermSession.expires * 1000),
                    httpOnly: true
                })
            }

            req.session.rboxlo.user = (await user.getNecessarySessionInfoForUser(response.userId))

            if (req.session.rboxlo.hasOwnProperty("redirect")) {
                delete req.session.rboxlo.redirect
                return res.redirect(req.session.rboxlo.redirect)
            } else {
                return res.redirect("/my/dashboard")
            }
        }

        for (const [target, value] of Object.entries(response.targets)) {
            objects.form[target].invalid = true
            objects.form[target].message = value
        }

        if (req.session.rboxlo.hasOwnProperty("redirect")) {
            delete req.session.rboxlo.redirect
        }

        if (response.targets.hasOwnProperty("username") && response.targets.username == "Invalid username or password.") {
            delete objects.form.password
        }
        
        res.render("account/login", { "title": "Login", "objects": objects })
    })
}

router.post("/register", user.loggedOut, createAccount)
router.post("/login", user.loggedOut, authenticate)

router.get("/register", user.loggedOut, (req, res) => {
    if (!global.rboxlo.env.PRIVATE_REGISTRATION) {
        return res.sendStatus(403)
    }
    
    res.render("account/register", { "title": "Register", "objects": { csrf: req.csrfToken() } })
})

router.get("/login", user.loggedOut, async (req, res) => {
    let challenge = (await user.needsAuthenticationChallenge(req.rboxlo.ip))
    res.render("account/login", { "title": "Login", "objects": { "csrf": req.csrfToken(), "challenge": challenge } })
})

router.get("/logout", (req, res) => {
    if (req.session.rboxlo.hasOwnProperty("user")) {
        delete req.session.rboxlo.user
    }

    if (req.cookies.hasOwnProperty("remember_me")) {
        res.clearCookie("remember_me")
    }

    res.redirect("/account/login")
})

module.exports = router