var router = require("express").Router()

const path = require("path")

const user = require(path.join(global.rboxlo.root, "lib", "user"))

router.get("/", user.loggedOut, (req, res) => {
    res.render("home", { title: "Home" })
})

router.get("/login", user.loggedOut, (req, res) => {
    res.redirect("/account/login")
})

module.exports = router