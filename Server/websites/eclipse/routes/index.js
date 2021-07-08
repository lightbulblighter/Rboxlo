var router = require("express").Router()

const path = require("path")

// Static Views
router.use("/", require(path.join(__dirname, "main")))
router.use("/account", require(path.join(__dirname, "account")))
router.use("/my", require(path.join(__dirname, "personal")))

// Games
router.use("/games", require(path.join(__dirname, "games")))

// Data (thumbnails, place files, etc.)
router.use("/data", require(path.join(__dirname, "data")))

// API
router.use("/api", require(path.join(__dirname, "api")))

module.exports = router