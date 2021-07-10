var router = require("express").Router()

const path = require("path")
const compare = require("safe-compare")

const user = require(path.join(global.rboxlo.root, "lib", "user"))
const session = require(path.join(global.rboxlo.root, "lib", "session"))

router.get("/", user.authenticated, (req, res) => {
    res.render("home", { title: "Home" })
})

router.get("/logout", (req, res) => {
    session.clear(req)
    return res.redirect("/")
})

router.get("/login", (req, res) => {
    res.render("account/login", { layout: "form", title: "Login" })
})

router.post("/login", async (req, res) => {
    if (req.body.hasOwnProperty("password") && req.body.password.length > 0) {
        if (compare(global.rboxlo.env.TOOTSIE_PASSWORD, req.body.password)) {
            req.session.rboxlo.user = { in: true }
            return res.redirect("/")
        } else {
            res.render("account/login", { layout: "form", title: "Login" })
        }
    }
})

module.exports = router