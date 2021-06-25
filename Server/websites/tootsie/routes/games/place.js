var router = require("express").Router()

const path = require("path")

const user = require(path.join(global.rboxlo.root, "websites", "tootsie", "lib", "user"))

router.get("/delete", user.authenticated, (req, res) => {
    res.render("games/place/delete", { title: "Delete Place", laid: "games.place.delete" })
})

module.exports = router