var router = require("express").Router()

const bytes = require("bytes")
const path = require("path")

const application = require(path.join(global.rboxlo.root, "lib", "application"))
const games = require(path.join(global.rboxlo.root, "lib", "games"))
const user = require(path.join(global.rboxlo.root, "lib", "user"))
const util = require(path.join(global.rboxlo.root, "lib", "base", "util"))

var maxPlaceSize = bytes(games.MAX_PLACE_SIZE, { decimalPlaces: 0 })

router.get("/new", user.authenticated, async (req, res) => {
    if (!req.session.rboxlo.user.permissions.places.creation) {
        return res.sendStatus(403)
    }

    res.render("games/places/new", { 
        title: "New Place",
        maxPlaceSize: bytes(games.MAX_PLACE_SIZE, { decimalPlaces: 0 }),
        applications: util.snakeCaseToCamelCaseArrayNested(await application.all())
    })
})

router.post("/new", user.authenticated, async (req, res) => {
    if (!req.session.rboxlo.user.permissions.places.creation) {
        return res.sendStatus(403)
    }

    let form = {
        name:        { invalid: false },
        description: { invalid: false },
        privacy:     { invalid: false },
        chat_style:  { invalid: false },
        genre:       { invalid: false },
        max_players: { invalid: false },
        application: { invalid: false },
        file:        { invalid: false }
    }

    if (global.rboxlo.env.GOOGLE_RECAPTCHA_ENABLED) {
        if (!form.hasOwnProperty("captcha") && (!req.body.hasOwnProperty("g-recaptcha-response") || req.body["g-recaptcha-response"].length == 0)) {
            form.captcha = { invalid: true, message: "Please solve the captcha challenge." }
        }

        if (!form.hasOwnProperty("captcha")) {
            if (!await user.verifyCaptcha(req.rboxlo.ip, req.body["g-recaptcha-response"])) {
                form.captcha = { invalid: true, message: "You failed to solve the captcha challenge. Please try again." }
            }
        }

        if (form.hasOwnProperty("captcha")) {
            form = {
                "captcha": form.captcha
            }

            return res.render("games/places/new", { title: "New Place", applications: util.snakeCaseToCamelCaseArrayNested(await application.all()), maxPlaceSize: maxPlaceSize, form: form })
        }
    }

    if (!req.files || !req.files.hasOwnProperty("file")) {
        form = { file: { invalid: false } }

        form.file.invalid = true
        form.file.message = "Please provide a place file."

        return res.render("games/places/new", { title: "New Place", applications: util.snakeCaseToCamelCaseArrayNested(await application.all()), maxPlaceSize: maxPlaceSize, form: form })
    }

    if (req.files.file.size > games.MAX_PLACE_SIZE) {
        form = { file: { invalid: false } }

        form.file.invalid = true
        form.file.message = `Place file is too large (maximum is ${maxPlaceSize}.)`

        return res.render("games/places/new", { title: "New Place", applications: util.snakeCaseToCamelCaseArrayNested(await application.all()), maxPlaceSize: maxPlaceSize, form: form })
    }

    games.createGameAndPlace(req.session.rboxlo.user.id, req.body).then(async (response) => {
        if (response.success === true) {
            await games.uploadPlaceFile(req.session.rboxlo.user.id, response.place.id, req.files.file)
            res.redirect(`/games/places/view?id=${response.place.id}`)

            return
        }

        // Failed
        for (const [target, value] of Object.entries(response.targets)) {
            objects.form[target].invalid = true
            objects.form[target].message = value
        }

        res.render("games/places/new", { title: "New Place", applications: util.snakeCaseToCamelCaseArrayNested(await application.all()), maxPlaceSize: maxPlaceSize, form: form })
    })
})

module.exports = router