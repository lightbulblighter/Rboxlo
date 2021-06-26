var router = require("express").Router()

const path = require("path")

const user = require(path.join(global.rboxlo.root, "websites", "tootsie", "lib", "user"))

router.get("/badges", user.authenticated, (req, res) => {
    res.render("games/place/badges", { title: "Manage Place Badges", laid: "games.place.badges" })
})

router.get("/delete", user.authenticated, (req, res) => {
    res.render("games/place/delete", { title: "Delete Place", laid: "games.place.delete" })
})

router.get("/file-upload", user.authenticated, (req, res) => {
    res.render("games/place/file-upload", { title: "Upload Custom File to Place", laid: "games.place.file-upload" })
})

router.get("/jobs", user.authenticated, (req, res) => {
    res.render("games/place/jobs", { title: "Manage Place Jobs", laid: "games.place.jobs" })
})

router.get("/marketplace", user.authenticated, (req, res) => {
    res.render("games/place/marketplace", { title: "Manage Place Marketplace", laid: "games.place.marketplace" })
})

router.get("/moderate", user.authenticated, (req, res) => {
    res.render("games/place/moderate", { title: "Moderate Place", laid: "games.place.moderate" })
})

router.get("/modify", user.authenticated, (req, res) => {
    res.render("games/place/modify", { title: "Modify Place", laid: "games.place.modify" })
})

router.get("/version-history", user.authenticated, (req, res) => {
    res.render("games/place/version-history", { title: "View Place Version History", laid: "games.place.version-history" })
})

router.get("/view", user.authenticated, (req, res) => {
    res.render("games/place/view", { title: "View Place", laid: "games.place.view" })
})

module.exports = router