var router = require("express").Router()

const path = require("path")

const application = require(path.join(global.rboxlo.root, "lib", "application"))
const util = require(path.join(global.rboxlo.root, "lib", "base", "util"))

router.use("/places", require(path.join(__dirname, "places")))

router.get("/", async (req, res) => {
    res.render("games/index", { title: "Games", applications: util.snakeCaseToCamelCaseArrayNested(await application.all()) })
})

module.exports = router