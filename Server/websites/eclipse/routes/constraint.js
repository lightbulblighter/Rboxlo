var router = require("express").Router()

const path = require("path")

const user = require(path.join(global.rboxlo.root, "lib", "user"))

router.get("/constraint", user.loggedOut, async (req, res) => {
    let challenge = await user.needsAuthenticationChallenge(req.rboxlo.ip)
    res.render("constraint", { layout: "constraint", title: "Login", challenge: challenge })
})

router.post("/constraint", user.loggedOut, async (req, res) => {
    let challenge = await user.needsAuthenticationChallenge(req.rboxlo.ip)
    let response

    if (global.rboxlo.env.GOOGLE_RECAPTCHA_ENABLED) {
        if (await user.needsAuthenticationChallenge(req.rboxlo.ip)) {
            if (!response && (!req.body.hasOwnProperty("g-recaptcha-response") || req.body["g-recaptcha-response"].length == 0)) {
                response = "Please solve the captcha challenge."
            }

            if (!response) {
                if (!user.verifyCaptcha(req.body["g-recaptcha-response"])) {
                    response = "You failed to solve the captcha challenge. Please try again."
                }
            }

            if (response) {
                return res.render("constraint", { layout: "constraint", title: "Login", challenge: challenge, response: response })
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
        let lastMessage = ""
        for (const [, value] of Object.entries(response.targets)) {
            lastMessage = value
        }
        response = lastMessage

        if (req.session.rboxlo.hasOwnProperty("redirect")) {
            delete req.session.rboxlo.redirect
        }

        res.render("constraint", { layout: "constraint", title: "Login", challenge: challenge, response: response })
    })
})

module.exports = router