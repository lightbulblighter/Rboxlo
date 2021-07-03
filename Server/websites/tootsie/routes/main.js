var router = require("express").Router()

const path = require("path")
const compare = require("safe-compare")

const kryptshun = require(path.join(global.rboxlo.root, "kryptshun"))
const user = require(path.join(global.rboxlo.root, "websites", "shared", "lib", "user"))

// LAID: Link Active ID
// Also what most software developers will never get before they turn 50

router.get("/", user.authenticated, (req, res) => {
    res.render("home", { title: "Home", laid: "dashboard" })
})

router.get("/logout", (req, res) => {
    if (req.session.rboxlo.hasOwnProperty("user")) {
        delete req.session.rboxlo.user
    }
    
    return res.redirect("/")
})

router.get("/login", (req, res) => {
    res.render("login", { layout: "form", title: "Login", objects: { csrf: req.csrfToken() } })
})

router.post("/login", async (req, res) => {
    if (req.body.hasOwnProperty("password") && req.body.password.length > 0) {
        if (compare(global.rboxlo.env.TOOTSIE_PASSWORD, req.body.password)) {
            req.session.rboxlo.user = { in: true }
            return res.redirect("/")
        } else {
            res.render("login", { layout: "form", title: "Login", objects: { csrf: req.csrfToken() } })
        }
    }
})

module.exports = router