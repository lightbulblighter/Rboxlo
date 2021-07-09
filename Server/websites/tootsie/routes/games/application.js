var router = require("express").Router()

const bytes = require("bytes")
const path = require("path")
const validator = require("validator")

const application = require(path.join(global.rboxlo.root, "lib", "application"))
const user = require(path.join(global.rboxlo.root, "lib", "user"))
const util = require(path.join(global.rboxlo.root, "lib", "base", "util"))

router.get("/instance-finder", user.authenticated, (req, res) => {
    res.render("games/application/instance-finder", { title: "Find Instances with Application" })
})

router.get("/jobs", user.authenticated, (req, res) => {
    res.render("games/application/jobs", { title: "Manage Jobs with Application" })
})

router.get("/delete", user.authenticated, (req, res) => {
    res.render("games/application/delete", { title: "Delete Application" })
})

router.get("/deployment", user.authenticated, async (req, res) => {
    // If no ID specified, render generic deployment view
    if (!req.query.hasOwnProperty("id")) {
        res.render("games/application/deployment", {
            title: "Deploy Application Software",
            laid: "games.application.deployment",
            maxLauncherSize: bytes(application.MAX_LAUNCHER_SIZE, { decimalPlaces: 0 }),
            maxApplicationSize: bytes(application.MAX_APPLICATION_SIZE, { decimalPlaces: 0 }),
            maxArbiterSize: bytes(application.MAX_ARBITER_SIZE, { decimalPlaces: 0 })
        })

        return
    }

    // If ID specified, render deployment view with selected application
    if (!isNaN(req.query.id) && validator.isInt(req.query.id)) {
        let id = parseInt(req.query.id)
        if (!await application.exists(id)) {
            return res.redirect("/games/application/deployment")
        }

        let app = await application.information(id)
        res.render("games/application/deployment", {
            title: "Deploy Application Software",
            laid: "games.application.deployment",
            maxLauncherSize: bytes(application.MAX_LAUNCHER_SIZE, { decimalPlaces: 0 }),
            maxApplicationSize: bytes(application.MAX_APPLICATION_SIZE, { decimalPlaces: 0 }),
            maxArbiterSize: bytes(application.MAX_ARBITER_SIZE, { decimalPlaces: 0 }),
            application: util.snakeCaseToCamelCaseArray(app)
        })

        return
    }

    // Only occurs when ID specified and it is invalid
    res.redirect("/games/application/deployment")
})

router.get("/modify", user.authenticated, async (req, res) => {
    // If no ID specified, render generic modification view
    if (!req.query.hasOwnProperty("id")) {
        return res.render("games/application/modify", { title: "Modify Application" })
    }

    // If ID specified, render modification view with selected application
    if (!isNaN(req.query.id) && validator.isInt(req.query.id)) {
        let id = parseInt(req.query.id)

        if (!await application.exists(id)) {
            return res.redirect("/games/application/modify")
        }

        let app = await application.information(id)
        res.render("games/application/modify", {
            title: "Modify Application",
            application: util.snakeCaseToCamelCaseArray(app)
        })
        
        return
    }
    
    // Only occurs when ID specified and it is invalid
    res.redirect("/games/application/modify")
})

router.get("/new", user.authenticated, async (req, res) => {
    res.render("games/application/new", { title: "New Application" })
})

router.post("/new", user.authenticated, async (req, res) => {
    // Checks
    let response = false

    if (!response && !req.body.hasOwnProperty("display_name") || req.body["display_name"].length < 0) {
        response = "No display name provided"
    }

    if (!response && !req.body.hasOwnProperty("internal_name") || req.body["internal_name"].length < 0) {
        response = "No internal name provided"
    }

    if (!response && !validator.isAlphanumeric(req.body["internal_name"])) {
        response = "Invalid internal name provided; internal name must only be alphanumeric characters"
    }

    // If failed any of the checks, render with error
    if (response !== false) {
        return res.render("games/application/new", {
            title: "New Application",
            response: response
        })
    }

    // Passed the checks, create application
    let id = await application.create(req.body["internal_name"], req.body["display_name"])
    res.redirect(`/games/application/modify?id=${id}`)
})

module.exports = router