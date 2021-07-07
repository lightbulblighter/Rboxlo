var router = require("express").Router()

const path = require("path")

router.use("/game", require(path.join(__dirname, "game")))

module.exports = router