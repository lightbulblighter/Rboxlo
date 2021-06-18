var router = require("express").Router()

const path = require("path")

const user = require(path.join(global.rboxlo.root, "websites", "eclipse", "lib", "user"))

router.get("/new", user.authenticated, (req, res) => {
    if (!req.session.rboxlo.user.permissions.places.creation) {
        return res.send(403)
    }

    res.render("games/places/new", { title: "New Game"})
})

module.exports = router