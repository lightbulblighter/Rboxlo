var router = require("express").Router()

const path = require("path")

const user = require(path.join(global.rboxlo.root, "websites", "shared", "lib", "user"))

router.get("/delete", user.authenticated, (req, res) => {
    res.render("games/instance/delete", { title: "Delete Instance", laid: "games.instance.delete" })
})

router.get("/jobs", user.authenticated, (req, res) => {
    res.render("games/instance/jobs", { title: "Manage Jobs with Instance", laid: "games.instance.jobs" })
})

router.get("/modify", user.authenticated, (req, res) => {
    res.render("games/instance/modify", { title: "Modify Instance", laid: "games.instance.modify" })
})

router.get("/register", user.authenticated, (req, res) => {
    res.render("games/instance/register", { title: "Register Machine as Instance", laid: "games.instance.register" })
})

router.get("/search", user.authenticated, (req, res) => {
    res.render("games/instance/search", { title: "Search for an Instance", laid: "games.instance.search" })
})

module.exports = router