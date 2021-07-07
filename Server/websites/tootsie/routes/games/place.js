var router = require("express").Router()

const path = require("path")

const user = require(path.join(global.rboxlo.root, "lib", "user"))

router.get("/badges", user.authenticated, (req, res) => {
    res.render("games/place/badges", { title: "Manage Place Badges" })
})

router.get("/delete", user.authenticated, (req, res) => {
    res.render("games/place/delete", { title: "Delete Place" })
})

router.get("/file-upload", user.authenticated, (req, res) => {
    res.render("games/place/file-upload", { title: "Upload Custom File to Place" })
})

router.get("/jobs", user.authenticated, (req, res) => {
    res.render("games/place/jobs", { title: "Manage Place Jobs" })
})

router.get("/marketplace", user.authenticated, (req, res) => {
    res.render("games/place/marketplace", { title: "Manage Place Marketplace" })
})

router.get("/modify", user.authenticated, (req, res) => {
    res.render("games/place/modify", { title: "Modify Place" })
})

router.get("/version-history", user.authenticated, (req, res) => {
    res.render("games/place/version-history", { title: "View Place Version History" })
})

router.get("/view", user.authenticated, (req, res) => {
    res.render("games/place/view", { title: "View Place" })
})

module.exports = router