var router = require("express").Router()

const compare = require("safe-compare")
const crypto = require("crypto")
const path = require("path")

const user = require(path.join(global.rboxlo.root, "websites", "tootsie", "lib", "user"))

// LAID: Link Active ID
// Also what most software developers will never get before they turn 50

router.get("/", user.authenticated, (req, res) => {
    res.render("home", { title: "Home", laid: "dashboard" })
})

router.get("/login", (req, res) => {
    res.render("login", { layout: "form", title: "Login" })
})

router.post("/login", (req, res) => {
    // TODO: This is super insecure and dumb way to lock down the most crucial part of the site
    // Migrate this to argon2 real soon, this is only sha256 to make it more simple
    if (req.body.hasOwnProperty("password") && req.body.password.length > 0) {
        let hash = crypto.createHash("sha256").update(req.body.password).digest("hex")
        if (compare(global.rboxlo.env.TOOTSIE_PASSWORD, hash)) {
            req.session.rboxlo.user = { in: true }
            return res.redirect("/")
        }
    }

    res.render("/login", { layout: "form", title: "Login" })
})

module.exports = router