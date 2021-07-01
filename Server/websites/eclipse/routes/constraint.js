var router = require("express").Router()

const path = require("path")

const user = require(path.join(global.rboxlo.root, "websites", "eclipse", "lib", "user"))

router.get("/constraint", user.loggedOut, async (req, res) => {
    let challenge = (await user.needsAuthenticationChallenge(req.rboxlo.ip))

    res.render("constraint", {
        layout: "constraint",
        title: "Login",
        objects: { 
            csrf: req.csrfToken(),
            "challenge": challenge
        }
    })
})

router.post("/constraint", user.loggedOut, async (req, res) => {
    console.log("A")
    let challenge = (await user.needsAuthenticationChallenge(req.rboxlo.ip))
    let output = {
        layout: "constraint",
        title: "Login",
        objects: { 
            csrf: req.csrfToken(),
            "challenge": challenge
        }
    }

    if (global.rboxlo.env.GOOGLE_RECAPTCHA_ENABLED) {
        if (await user.needsAuthenticationChallenge(req.rboxlo.ip)) {
            if (!output.objects.form.hasOwnProperty("response") && (!req.body.hasOwnProperty("g-recaptcha-response") || req.body["g-recaptcha-response"].length == 0)) {
                output.objects.form.response = "Please solve the captcha challenge."
            }

            if (!output.objects.form.hasOwnProperty("response")) {
                if (!user.verifyCaptcha(req.body["g-recaptcha-response"])) {
                    output.objects.form.response = "You failed to solve the captcha challenge. Please try again."
                }
            }

            if (output.objects.form.hasOwnProperty("response")) {
                return res.render("constraint", output)
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

        // Is this good UI? No. Do I care? Also no
        let lastMessage = ""
        for (const [target, value] of Object.entries(response.targets)) {
            lastMessage = value
        }
        output.objects.response = lastMessage

        if (req.session.rboxlo.hasOwnProperty("redirect")) {
            delete req.session.rboxlo.redirect
        }

        res.render("constraint", output)
    })
})

module.exports = router