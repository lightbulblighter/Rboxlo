var router = require("express").Router()

const csurf = require("csurf")
const bytes = require("bytes")
const path = require("path")

const application = require(path.join(global.rboxlo.root, "websites", "eclipse", "lib", "application"))
const games = require(path.join(global.rboxlo.root, "websites", "eclipse", "lib", "games"))
const user = require(path.join(global.rboxlo.root, "websites", "eclipse", "lib", "user"))

var csrf = csurf({ cookie: true })
var maxPlaceSize = bytes(games.MAX_PLACE_SIZE, { decimalPlaces: 0 })

// Applications fetched on startup
// Do this 20 seconds late to wait for database to startup
var applications = []
setTimeout(async () => {
    applications = application.fetchAll()
}, 20000)

router.get("/new", user.authenticated, csrf, (req, res) => {
    if (!req.session.rboxlo.user.permissions.places.creation) {
        return res.sendStatus(403)
    }

    res.render("games/places/new", { 
        title: "New Place", 
        objects: { 
            csrf: req.csrfToken(), 
            max: maxPlaceSize,
            versions: applications
        }
    })
})

router.post("/new", user.authenticated, csrf, async (req, res) => {
    if (!req.session.rboxlo.user.permissions.places.creation) {
        return res.sendStatus(403)
    }

    let objects = {
        "form": {
            "name": { invalid: false },
            "privacy": { invalid: false },
            "chat-style": { invalid: false },
            "genre": { invalid: false },
            "max-players": { invalid: false },
            "place": { invalid: false }
        },
        "csrf": req.csrfToken(),
        "max": maxPlaceSize,
        "versions": applications
    }

    if (global.rboxlo.env.GOOGLE_RECAPTCHA_ENABLED) {
        if (!objects.form.hasOwnProperty("captcha") && (!req.body.hasOwnProperty("g-recaptcha-response") || req.body["g-recaptcha-response"].length == 0)) {
            objects.form.captcha = { invalid: true, message: "Please solve the captcha challenge." }
        }

        if (!objects.form.hasOwnProperty("captcha")) {
            if (!(await user.verifyCaptcha(req.rboxlo.ip, req.body["g-recaptcha-response"]))) {
                objects.form.captcha = { invalid: true, message: "You failed to solve the captcha challenge. Please try again." }
            }
        }

        if (objects.form.hasOwnProperty("captcha")) {
            objects.form = {
                "captcha": objects.form.captcha,
                "csrf": objects.form.csrf
            }

            return res.render("games/places/new", { title: "New Place", "objects": objects })
        }
    }
    
    if (!req.files || !req.files.hasOwnProperty("place")) {
        objects.form.place.invalid = true
        objects.form.place.message = "Please provide a place file."

        return res.render("games/places/new", { title: "New Place", "objects": objects })
    }

    if (req.files.place.size > games.MAX_PLACE_SIZE) {
        objects.form.place.invalid = true
        objects.form.place.message = `Place file is too large (maximum is ${maxPlaceSize}.)`

        return res.render("games/places/new", { title: "New Place", "objects": objects })
    }

    games.createGameAndPlace(req.session.rboxlo.user.id, req.body).then(async (response) => {
        if (response.success === true) {
            await games.uploadPlaceFile(req.session.rboxlo.user.id, response.place.id, req.files.place)
            return res.redirect(`/games/places/view?id=${response.place.id}`)
        }

        for (const [target, value] of Object.entries(response.targets)) {
            objects.form[target].invalid = true
            objects.form[target].message = value
        }
    })

    res.render("games/places/new", { title: "New Place", "objects": objects })
})

module.exports = router