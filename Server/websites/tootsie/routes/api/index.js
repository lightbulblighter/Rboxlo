var router = require("express").Router()

const path = require("path")

router.use("/games", require(path.join(__dirname, "games")))

module.exports = router