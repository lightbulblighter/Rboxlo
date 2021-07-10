var router = require("express").Router()

const path = require("path")

router.get("/ok", (req, res) => {
    return res.json({ success: true })
})

router.use("/games", require(path.join(__dirname, "/games")))

module.exports = router