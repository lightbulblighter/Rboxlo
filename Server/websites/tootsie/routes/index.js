var router = require("express").Router()

const path = require("path")

// Main routes
router.use("/", require(path.join(__dirname, "main")))
router.use("/account", require(path.join(__dirname, "account")))
router.use("/games", require(path.join(__dirname, "games")))

// API
router.use("/api", require(path.join(__dirname, "api")))

module.exports = router