var router = require("express").Router()

const path = require("path")

router.use("/application", require(path.join(__dirname, "application")))
router.use("/game", require(path.join(__dirname, "game")))
router.use("/instance", require(path.join(__dirname, "instance")))
router.use("/place", require(path.join(__dirname, "place")))

module.exports = router