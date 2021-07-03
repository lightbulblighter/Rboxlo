var router = require("express").Router()

const path = require("path")

const user = require(path.join(global.rboxlo.root, "websites", "shared", "lib", "user"))

router.get("/delete", user.authenticated, (req, res) => {
    res.render("games/game/delete", { title: "Delete Game", laid: "games.game.delete" })
})

router.get("/jobs", user.authenticated, (req, res) => {
    res.render("games/game/jobs", { title: "Manage Game Jobs", laid: "games.game.jobs" })
})

router.get("/modify", user.authenticated, (req, res) => {
    res.render("games/game/modify", { title: "Modify Game", laid: "games.game.modify" })
})

router.get("/view", user.authenticated, (req, res) => {
    res.render("games/game/view", { title: "View Game", laid: "games.game.view" })
})

module.exports = router