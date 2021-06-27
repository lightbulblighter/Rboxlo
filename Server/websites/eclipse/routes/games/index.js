var router = require("express").Router()

const path = require("path")

const user = require(path.join(global.rboxlo.root, "websites", "eclipse", "lib", "user"))

router.get("/", (req, res) => {
    res.render("games/index", { title: "Games" })
})

module.exports = router