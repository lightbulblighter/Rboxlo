var router = require("express").Router()

const path = require("path")

const application = require(path.join(global.rboxlo.root, "websites", "shared", "lib", "application"))
const user = require(path.join(global.rboxlo.root, "websites", "shared", "lib", "user"))

router.get("/", async (req, res) => {
    let applications = await application.fetchAll()
    res.render("games/index", { title: "Games", objects: { versions: applications } })
})

module.exports = router