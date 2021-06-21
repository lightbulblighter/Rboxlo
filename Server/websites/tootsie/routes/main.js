var router = require("express").Router()

const path = require("path")

router.get("/", (req, res) => {
    res.render("home", { title: "Master" })
})

module.exports = router