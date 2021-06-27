var exports = module.exports = {}

const bytes = require("bytes")
const fs = require("fs")
const moment = require("moment")
const path = require("path")
const uuid = require("uuid")
const xss = require("xss")
const sha256File = require("sha256-file")

const application = require(path.join(global.rboxlo.root, "websites", "eclipse", "lib", "application"))
const sql = require(path.join(global.rboxlo.root, "sql"))

/**
 * Maximum place file size, in bytes
 */
const MAX_PLACE_SIZE = bytes("100MB")
exports.MAX_PLACE_SIZE = MAX_PLACE_SIZE

/**
 * Creates allowed gears JSON string
 * 
 * @param {array} information Untrusted user information
 * 
 * @returns {string} Allowed gears JSON
 */
function createAllowedGearsJSON(information) {
    let gears = [
        "melee",
        "powerup",
        "ranged",
        "navigation",
        "explosive",
        "musical",
        "social",
        "transport",
        "building"
    ]

    // stupid failsafe for spamming since information is untrusted
    if (Object.keys(information).length > 20) {
        return JSON.stringify([])
    }

    let result = []
    for (const [key, value] of Object.entries(information)) {
        if (key.includes("gear-") && value == "on") {
            let trimmed = key.slice("gear-".length)
            if (gears.includes(trimmed)) {
                result.push(trimmed)
            }
        }
    }

    return JSON.stringify(result)
}

/**
 * Creates a place
 * 
 * @param {number} gameID Parent game ID
 * @param {string} gameUUID Parent game UUID
 * @param {string} name Place name
 * @param {string} description Place description (default: "No description provided")
 * @param {number} genre Place genre (min: 1, max: 16)
 * @param {number} maxPlayers Max players (min: 1, max: 100)
 * @param {number} copying Place is copying (is a number so 0 false 1 true) default: FALSE (0)
 * @param {string} allowedGearsJSON Allowed gears in JSON string form
 * @param {number} chatStyle Chat style (0: classic, 1: bubble, 2: both)
 * @param {number} isStartPlace If this place is a start place
 * 
 * @returns {array|boolean} Returns false if error (only potential error is if couldn't find game) or returns place information
 */
exports.createPlace = async (gameID, name, description, genre, maxPlayers, copying = 0, allowedGearsJSON, chatStyle, isStartPlace) => {
    let gameUUID = (await sql.run("SELECT `uuid` FROM `games` WHERE `id` = ?", [gameID]))
    if (gameUUID.length == 0) {
        return false
    }
    gameUUID = gameUUID[0].uuid
    
    name = xss(name)
    description = xss(description)

    let placeUUID = uuid.v4()

    await sql.run(
        "INSERT INTO `places` (`game_id`, `game_uuid`, `uuid`, `name`, `description`, `genre`, `max_players`, `copying`, `allowed_gears`, `chat_style`, `is_start_place`, `created_timestamp`, `last_updated`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
        [gameID, gameUUID, placeUUID, name, description, genre, maxPlayers, copying, allowedGearsJSON, chatStyle, isStartPlace, moment().unix(), moment().unix()]
    )

    let place = (await sql.run("SELECT `id` FROM `places` WHERE `uuid` = ?", [placeUUID]))[0]

    if (isStartPlace) {
        await sql.run(
            "UPDATE `games` SET `start_place_id` = ? WHERE `id` = ?",
            [place.id, gameID]
        )
    }

    return {
        id: place.id,
        uuid: placeUUID
    }
}

/**
 * Creates a game (otherwise known as, in the Roblox world, a Universe (now "Experiences"))
 * 
 * @param {number} userID The userID that will be given full permissions to and is internally the de facto owner. There has to be at least ONE user with full permissions.
 * @param {number} startPlaceID  Start place
 * @param {string} name Name of the game
 * @param {string} app Application
 * @param {number} privacy Privacy settings (0: "Everyone", 1: "My friends", 2: "Only me") assuming from the perspective of the user with ID specified here
 * 
 * @returns {array|boolean} Returns false if error (only potential error is if couldn't find place or invalid app) or returns game information
 */
exports.createGame = async (userID, startPlaceID, name, app, privacy) => {
    if (!application.exists(app)) {
        return false
    }

    name = xss(name)

    let privileges = [
        {
            "id": userID,
            "permissions": ["edit", "copy", "delete", "watchdog", "ALL", {"places": ["ALL"]}]
        }
    ]
    privileges = JSON.stringify(privileges)

    let gameUUID = uuid.v4()

    await sql.run(
        "INSERT INTO `games` (`uuid`, `start_place_id`, `created_timestamp`, `last_updated_timestamp`, `name`, `application`, `privileges`, `privacy`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
        [gameUUID, startPlaceID, moment().unix(), moment().unix(), name, app, privileges, privacy]
    )

    let game = (await sql.run("SELECT `id`, `uuid` FROM `games` WHERE `uuid` = ?", [gameUUID]))[0]

    return {
        id: game.id,
        uuid: game.uuid
    }
}

/**
 * Creates a game and place at the same time. Traditionally used with place creation forms, so has built in form checks that interface with regular forms.
 * 
 * @param {number} userID Creator user ID
 * @param {array} information Place information array 
 * @param {boolean} formChecks Whether to check all the information to see if it's valid (default: TRUE)
 * 
 * @returns {Promise} Returns a promise that resolves with a success boolean and "targets" assuming formChecks is true and also place/game info
 */
exports.createGameAndPlace = (userID, information, formChecks = true) => {
    return new Promise(async (resolve, reject) => {
        let response = {"success" : false}

        if (formChecks) {
            response.targets = {}

            {
                if (!response.targets.hasOwnProperty("name") && (!information.hasOwnProperty("name") || information.name.length == 0)) {
                    response.targets.name = "Please choose a name for your place."
                }
    
                if (!response.targets.hasOwnProperty("name") && (information.name.length > 30)) {
                    response.targets.name = "The name of your place must be under thirty characters."
                }
    
                if (!response.targets.hasOwnProperty("name") && (information.name.length < 2)) {
                    response.targets.name = "The name of your place must be over two characters."
                }
    
                if (!response.targets.hasOwnProperty("privacy") && (!information.hasOwnProperty("privacy") || information.privacy.length == 0)) {
                    response.targets.privacy = "You must set a privacy filter for your place."
                }
    
                if (!response.targets.hasOwnProperty("privacy") && (information.privacy > 3 || information.privacy < 1)) {
                    response.targets.privacy = "Invalid privacy filter."
                }
    
                if (!response.targets.hasOwnProperty("chat_style") && (!information.hasOwnProperty("chat_style") || information["chat_style"].length == 0)) {
                    response.targets["chat_style"] = "You must set a chat style for your place."
                }
    
                if (!response.targets.hasOwnProperty("chat_style") || (information["chat_style"] > 2 || information["chat_style"] < 0)) {
                    response.targets["chat_style"] = "Invalid chat style."
                }
    
                if (!response.targets.hasOwnProperty("genre") && (!information.hasOwnProperty("genre") || information.genre.length == 0)) {
                    response.targets.genre = "You must choose a genre for your place."
                }
    
                if (!response.targets.hasOwnProperty("genre") || (information.genre > 13 || information.genre < 0)) {
                    response.targets.genre = "Invalid genre."
                }
    
                if (!response.targets.hasOwnProperty("max_players") && (!information.hasOwnProperty("max_players") || information["max_players"].length == 0)) {
                    response.targets["max_players"] = "You must set a max players amount for your place."
                }
    
                if (!response.targets.hasOwnProperty("max_players") && (information["max_players"] < 1)) {
                    response.targets["max_players"] = "The maximum players amount must be over zero."
                }
    
                if (!response.targets.hasOwnProperty("max_players") && (information["max_players"] > 100)) {
                    response.targets["max_players"] = "The maximum amount of players you can have in a place is 100."
                }
    
                if (!response.targets.hasOwnProperty("application") && (!information.application || information.application.length == 0)) {
                    response.targets.application = "Please select an application."
                }
    
                if (!response.targets.hasOwnProperty("application") && (!application.exists(information.application))) {
                    response.targets.application = "Invalid application."
                }
    
                if (!response.targets.hasOwnProperty("description") && (information.hasOwnProperty("description") && information.description.length > 2000)) {
                    response.targets.description = "Description must be under 2000 characters."
                }
            }
            
            if (response.targets.length > 0) {
                return resolve(response)
            }
        }

        let game = (await exports.createGame(userID, 0, information.name, information.application, information.privacy))
        let place = (await exports.createPlace(game.id, information.name, information.description, information.genre, information["max_players"], information.copying, createAllowedGearsJSON(information), information["chat_style"], true))

        response.game = game
        response.place = place
        response.success = true
        
        return resolve(response)
    })
}

/**
 * Uploads a place file to specified place
 * 
 * @param {number} userID User who uploading this
 * @param {number} placeID place ID
 * @param {} file Place file
 * 
 * @returns {boolean} Returns false if the file is too large, true otherwise
 */
exports.uploadPlaceFile = async (userID, placeID, file) => {
    // File operations
    let name = `temp${Math.floor(Math.random() * (999999 - 100000) + 100000)}`
    let tempPath = path.join(global.rboxlo.root, "data", "temp", name)
    let destinationPath = path.join(global.rboxlo.root, "data", "assets", "places", placeID.toString())

    await file.mv(tempPath)
    
    if (fs.statSync(tempPath).size > MAX_PLACE_SIZE) {
        return false
    }

    let hash = await sha256File(tempPath)
    let newVersion = fs.existsSync(destinationPath)
    let currentVersion = 1

    if (newVersion) {
        currentVersion = Math.max(fs.readdirSync(destinationPath)) + 1
    }

    if (!newVersion) {
        fs.mkdirSync(destinationPath)
    }

    fs.mkdirSync(path.join(destinationPath, currentVersion.toString()))
    fs.renameSync(tempPath, path.join(destinationPath, currentVersion.toString(), hash))

    // Make an entry in the database
    await sql.run(
        "INSERT INTO `place_versions` (`uploader_user_id`, `version`, `sha256`, `place_id`, `created_timestamp`) VALUES (?, ?, ?, ?, ?)",
        [userID, currentVersion, hash, placeID, moment().unix()]
    )

    return true
}

/**
 * Returns number of players active on a place
 * 
 * @param {number} placeID Place ID
 * @returns Number of active players
 */
exports.getActivePlayersByPlaceID = async (placeID) => {
    return 0
}