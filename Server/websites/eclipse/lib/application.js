var exports = module.exports = {}

const moment = require("moment")
const path = require("path")
const uuid = require("uuid")

const sql = require(path.join(global.rboxlo.root, "sql"))

/**
 * Checks if a given application exists
 * 
 * @param {string} name Application name or ID
 * @returns {boolean} If the application exists
 */
exports.exists = async (input) => {
    // DEBUG
    if (input == "debug") return true
    
    let result = (await sql.run("SELECT 1 FROM `applications` WHERE `name` = ? OR `id` = ?", [input, input]))

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
        "INSERT INTO `applications` (`uuid`, `last_deployed_version_uuid`, `created_timestamp`, `last_updated_timestamp`, `internal_name`, `display_name`) VALUES (?, ?, ?, ?)",
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