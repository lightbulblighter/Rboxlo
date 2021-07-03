var router = require("express").Router()

const fs = require("fs")
const path = require("path")
const validator = require("validator")

const user = require(path.join(global.rboxlo.root, "websites", "shared", "lib", "user"))

// This route is authbarred
router.get("/thumbnail/user", user.authenticated, (req, res) => {
    if (!req.query.hasOwnProperty("id")) {
        return res.sendStatus(404)
    }

    let id = parseInt(req.query.id)
    if (isNaN(id) || !validator.isInt(req.query.id)) {
        return res.sendStatus(404)
    }

    let file = path.join(global.rboxlo.root, "data", "thumbnails", "users", `${id}.png`)
    if (!fs.existsSync(file)) {
        return res.sendStatus(404)
    }

    let data = fs.createReadStream(file)
    res.writeHead(200, { "Content-Type": "image/png" })
    data.pipe(res)
})

module.exports = router