var router = require("express").Router()

const path = require("path")

router.use("/application", require(path.join(__dirname, "application")))

module.exports = router