var router = require("express").Router()

const path = require("path")

const user = require(path.join(global.rboxlo.root, "websites", "eclipse", "lib", "user"))

router.get("/", user.authenticated, (req, res) => {
    res.render("games/index", { title: "Games", objects: { canCreateGames: req.session.rboxlo.user.permissions.places.creation } })
})

router.get("/new", user.authenticated, (req, res) => {
    if (!req.session.rboxlo.user.permissions.places.creation) {
        return res.send(403)
    }

    res.render("games/new", { title: "New Game"})
})

module.exports = router