var router = require("express").Router()

const path = require("path")

const user = require(path.join(global.rboxlo.root, "lib", "user"))

router.get("/delete", user.authenticated, (req, res) => {
    res.render("games/game/delete", { title: "Delete Game" })
})

router.get("/jobs", user.authenticated, (req, res) => {
    res.render("games/game/jobs", { title: "Manage Game Jobs" })
})

router.get("/modify", user.authenticated, (req, res) => {
    res.render("games/game/modify", { title: "Modify Game" })
})

router.get("/view", user.authenticated, (req, res) => {
    res.render("games/game/view", { title: "View Game" })
})

module.exports = router