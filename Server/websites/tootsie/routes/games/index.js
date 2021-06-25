var router = require("express").Router()

const path = require("path")

router.use("/place", require(path.join(__dirname, "place")))

module.exports = router