var router = require("express").Router()

const bytes = require("bytes")
const path = require("path")
const validator = require("validator")

const application = require(path.join(global.rboxlo.root, "websites", "eclipse", "lib", "application"))
const games = require(path.join(global.rboxlo.root, "websites", "eclipse", "lib", "games"))
const user = require(path.join(global.rboxlo.root, "websites", "eclipse", "lib", "user"))
const sql = require(path.join(global.rboxlo.root, "sql"))

var maxPlaceSize = bytes(games.MAX_PLACE_SIZE, { decimalPlaces: 0 })

// Applications fetched on startup
// Do this 1 minute late to wait for database to startup
var applications = []
setTimeout(async () => {
    applications = application.fetchAll()
}, 60000)

router.get("/new", user.authenticated, (req, res) => {
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

router.post("/new", user.authenticated, async (req, res) => {
    if (!req.session.rboxlo.user.permissions.places.creation) {
        return res.sendStatus(403)
    }

    let objects = {
        "form": {
            "name": { invalid: false },
            "description": { invalid: false },
            "privacy": { invalid: false },
            "chat_style": { invalid: false },
            "genre": { invalid: false },
            "max_players": { invalid: false },
            "application": { invalid: false },
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
            res.redirect(`/games/places/view?id=${response.place.id}`)
            return
        }

        for (const [target, value] of Object.entries(response.targets)) {
            objects.form[target].invalid = true
            objects.form[target].message = value
        }

        res.render("games/places/new", { title: "New Place", "objects": objects })
    })
})

router.get("/json", async (req, res) => {
    let limit = 25
    let pageNumber = 1

    if (req.query.hasOwnProperty("page") && !isNaN(req.query.page) && validator.isInt(req.query.id)) {
        pageNumber = parseInt(req.query.page)
    }

    let startFrom = (pageNumber - 1) * limit

    let result = []
    let places = (await sql.run(`SELECT \`id\`, \`game_id\`, \`game_uuid\`, \`uuid\`, \`name\`, \`is_start_place\`, \`visits\` FROM \`places\` LIMIT ${startFrom}, ${limit}`,))

    // sift through places to get:
    // a. assign currently playing to each place
    // b. sort places by currently playing
    // c. normalize column names and create a games array for each place
    for (i = 0; i < places.length; i++) { 
        let place = places[i]
        
        result.push({
            id: place.id,
            uuid: place.uuid,
            name: place.name,
            isStartPlace: (place.is_start_place == 0 ? false : true),
            visits: place.visits,
            activePlayers: await (games.getActivePlayersByPlaceID(place.id)),
            game: {
                id: place.game_id,
                uuid: place.game_uuid
            }
        })
    }

    // sort
    result.sort((a, b) => { (a.activePlayers > b.activePlayers) ? 1: -1 })

    // return
    res.json(result)
})

module.exports = router