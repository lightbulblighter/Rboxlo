var router = require("express").Router()

const path = require("path")

router.use("/places", require(path.join(__dirname, "places")))

module.exports = router