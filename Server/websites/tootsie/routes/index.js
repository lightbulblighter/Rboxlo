var router = require("express").Router()

const path = require("path")

// Static Views
router.use("/", require(path.join(__dirname, "main")))

module.exports = router