var router = require("express").Router()

const path = require("path")

const user = require(path.join(global.rboxlo.root, "websites", "eclipse", "lib", "user"))

router.get("/", user.loggedOut, (req, res) => {
    res.render("home", { title: "Home", objects: { csrf: req.csrfToken() } })
})

module.exports = router