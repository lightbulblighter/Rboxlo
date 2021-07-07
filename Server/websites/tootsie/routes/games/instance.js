var router = require("express").Router()

const path = require("path")

const user = require(path.join(global.rboxlo.root, "lib", "user"))

router.get("/delete", user.authenticated, (req, res) => {
    res.render("games/instance/delete", { title: "Delete Instance" })
})

router.get("/jobs", user.authenticated, (req, res) => {
    res.render("games/instance/jobs", { title: "Manage Jobs with Instance" })
})

router.get("/modify", user.authenticated, (req, res) => {
    res.render("games/instance/modify", { title: "Modify Instance" })
})

router.get("/register", user.authenticated, (req, res) => {
    res.render("games/instance/register", { title: "Register Machine as Instance" })
})

router.get("/search", user.authenticated, (req, res) => {
    res.render("games/instance/search", { title: "Search for an Instance" })
})

module.exports = router