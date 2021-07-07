var router = require("express").Router()

const path = require("path")

router.use("/thumbnail", require(path.join(__dirname, "thumbnail")))

module.exports = router