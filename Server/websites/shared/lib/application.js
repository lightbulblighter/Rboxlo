var exports = module.exports = {}

const bytes = require("bytes")
const moment = require("moment")
const path = require("path")
const uuid = require("uuid")

const sql = require(path.join(global.rboxlo.root, "sql"))

/**
 * Maximum application file size, in bytes
 */
const MAX_PLACE_SIZE = bytes("100MB")
exports.MAX_PLACE_SIZE = MAX_PLACE_SIZE

/**
 * Maximum launcher file size, in bytes
 */
const MAX_LAUNCHER_SIZE = bytes("25MB")
exports.MAX_LAUNCHER_SIZE = MAX_LAUNCHER_SIZE

/**
 * Maximum arbiter file size, in bytes
 */
const MAX_ARBITER_SIZE = bytes("25MB")
exports.MAX_ARBITER_SIZE = MAX_ARBITER_SIZE

/**
 * Checks if a given application exists
 * 
 * @param {string} name Application internal name or ID
 * @returns {boolean} If the application exists
 */
exports.exists = async (input) => {
    // DEBUG
    if (input == "debug") return true
    
    let result = (await sql.run("SELECT 1 FROM `applications` WHERE `internal_name` = ? OR `id` = ?", [input, input]))

    return !(result.length == 0)
}

/**
 * Returns an array of application names
 * 
 * @returns {array} Application names
 */
exports.fetchAll = async () => {
    let result = (await sql.run("SELECT * FROM `applications`"))

    return result
}

/**
 * Creates an application
 * 
 * @param {string} internalName Application internal name
 * @param {string} displayName Application display name
 * @returns {number} id of new application
 */
exports.create = async (internalName, displayName) => {
    let appUUID = uuid.v4()
    let time = moment().unix()

    await sql.run(
        "INSERT INTO `applications` (`uuid`, `last_deployed_version_uuid`, `created_timestamp`, `last_updated_timestamp`, `internal_name`, `display_name`) VALUES (?, ?, ?, ?, ?, ?)",
        [appUUID, "", time, time, internalName, displayName]
    )

    let result = (await sql.run("SELECT `id` FROM `applications` WHERE `uuid` = ?", appUUID))
    return result[0].id
}

/**
 * Gets application info by ID
 * 
 * @param {number} id app id
 * @returns {array|boolean} result if exists, false if not
 */
exports.getInfo = async (id) => {
    if (!(await exports.exists(id))) {
        return false
    }
    
    let result = await sql.run("SELECT * FROM `applications` WHERE `id` = ?", id)
    return result[0]
}

/**
 * Updates an applications UUID
 * 
 * @param {number} id ID of the application
 * @param {string} newUUID new UUID
 * 
 * @returns {undefined|boolean} false if app doesn't exist
 */
exports.updateUUID = async (id, newUUID) => {
    if (!(await exports.exists(id))) {
        return false
    }

    await sql.run("UPDATE `applications` SET `uuid` = ? WHERE `id` = ?", [newUUID, id])
}

/**
 * Updates an applications last updated timestamp
 * 
 * @param {number} id ID of the application
 * 
 * @returns {boolean} false if app doesn't exist
 */
exports.setLastUpdated = async (id) => {
    if (!(await exports.exists(id))) {
        return false
    }

    await sql.run("UPDATE `applications` SET `last_updated_timestamp` = ? WHERE `id` = ?", [moment().unix(), id])
}

/**
 * Updates an applications internal name
 * 
 * @param {number} id ID of the application
 * @param {string} name new itnernal name
 * 
 * @returns {boolean} false if app doesn't exist
 */
exports.updateInternalName = async (id, name) => {
    if (!(await exports.exists(id))) {
        return false
    }
    
    await sql.run("UPDATE `applications` SET `internal_name` = ? WHERE `id` = ?", [name, id])
}

/**
 * Updates an applications internal name
 * 
 * @param {number} id ID of the application
 * @param {string} name new display name
 * 
 * @returns {boolean} false if app doesn't exist
 */
exports.updateDisplayName = async (id, name) => {
    if (!(await exports.exists(id))) {
        return false
    }
    
    await sql.run("UPDATE `applications` SET `display_name` = ? WHERE `id` = ?", [name, id])
}

/**
 * Deletes application
 * 
 * @param {number} id ID of the application
 * 
 * @returns {boolean} false if app doesn't exist
 */
exports.delete = async (id) => {
    if (!(await exports.exists(id))) {
        return false
    }

    await sql.run("DELETE FROM `applications` WHERE `id` =? ", id)
}