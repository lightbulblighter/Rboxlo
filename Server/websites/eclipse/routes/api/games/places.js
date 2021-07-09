var router = require("express").Router()

const path = require("path")

const games = require(path.join(global.rboxlo.root, "lib", "games"))

router.get("/json", async (req, res) => {
    let limit = 25
    let pageNumber = 1

    if (req.query.hasOwnProperty("page") && !isNaN(req.query.page) && validator.isInt(req.query.page)) {
        pageNumber = parseInt(req.query.page)
    }

    let startFrom = (pageNumber - 1) * limit

    // SECURITY: Escaping SQL without prepares.
    let result = []
    let places = await sql.run(`SELECT \`id\`, \`game_id\`, \`game_uuid\`, \`uuid\`, \`name\`, \`is_start_place\`, \`visits\` FROM \`places\` LIMIT ${startFrom}, ${limit}`)

    // Filter place list in order to:
    // A: Assign the number of active players for each place
    // B: Normalize the column names of the place and create a separate game array for each place
    // C: Sort places by currently playing
    for (let i = 0; i < places.length; i++) { 
        let place = places[i]
        
        result.push({
            id: place.id,
            uuid: place.uuid,
            name: place.name,
            isStartPlace: (place.is_start_place == 1 ? true : false),
            visits: place.visits,
            activePlayers: await games.getActivePlayersByPlaceID(place.id),
            game: {
                id: place.game_id,
                uuid: place.game_uuid
            }
        })
    }

    // Sort by active players
    result.sort((a, b) => { (a.activePlayers > b.activePlayers) ? 1: -1 })

    // Output
    res.json(result)
})

module.exports = router