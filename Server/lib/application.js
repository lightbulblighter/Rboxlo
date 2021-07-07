var exports = module.exports = {}

const bytes = require("bytes")
const moment = require("moment")
const path = require("path")
const uuid = require("uuid")

const sql = require(path.join(__dirname, "base", "sql"))

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
 * Maximum application download size, in bytes
 */
const MAX_APPLICATION_SIZE = bytes("25MB")
exports.MAX_APPLICATION_SIZE = MAX_APPLICATION_SIZE

/**
 * Checks if a given application exists
 * 
 * @param {string} name Application internal name or ID
 * @param {boolean} checkInternalName Whether to also check if given input matches an internal name with an application
 * 
 * @returns {boolean} If the application exists
 */
exports.exists = async (input, checkInternalName = false) => {
    let result = []
    if (checkInternalName) {
        result = await sql.run("SELECT 1 FROM `applications` WHERE `internal_name` = ? OR `id` = ?", [input, input])
    } else {
        result = await sql.run("SELECT 1 FROM `applications` WHERE `id` = ?", input)
    }
    
    return result.length > 0
}

/**
 * Returns an array of application names
 * 
 * @returns {array} Application names
 */
exports.all = async () => {
    return await sql.run("SELECT * FROM `applications`")
}

/**
 * Creates an application
 * 
 * @param {string} internalName Application internal name
 * @param {string} displayName Application display name
 * 
 * @returns {number} The ID of the new application
 */
exports.create = async (internalName, displayName) => {
    let _uuid = uuid.v4()
    let time = moment().unix()

    await sql.run(
        "INSERT INTO `applications` (`uuid`, `last_deployed_version_uuid`, `created_timestamp`, `last_updated_timestamp`, `internal_name`, `display_name`) VALUES (?, ?, ?, ?, ?, ?)",
        [_uuid, "", time, time, internalName, displayName]
    )

    return (await sql.run("SELECT `id` FROM `applications` WHERE `uuid` = ?", _uuid))[0].id
}

/**
 * Gets application info by ID. You should always call application.exists before using this
 * 
 * @param {number} id ID of the application
 * 
 * @returns {array} Application information
 */
exports.information = async (id) => {
    return (await sql.run("SELECT * FROM `applications` WHERE `id` = ?", id))[0]
}

/**
 * Updates an applications last updated timestamp. You should always call application.exists before using this
 * 
 * @param {number} id ID of the application
 */
exports.updateLastUpdatedTimestamp = async (id) => {
    await sql.run("UPDATE `applications` SET `last_updated_timestamp` = ? WHERE `id` = ?", [moment().unix(), id])
}

/**
 * Deletes application. You should always call application.exists before using this
 * 
 * @param {number} id ID of the application
 */
exports.delete = async (id) => {
    await sql.run("DELETE FROM `applications` WHERE `id` = ?", id)
}

/**
 * Checks if a column exists in the applications table
 * 
 * @param {string} column Colum name 
 * 
 * @returns {boolean} If the column exists
 */
exports.columnExists = async (column) => {
    return (await sql.run("SHOW COLUMNS FROM `applications` LIKE ?", column)).length > 0
}

/**
 * Sets the value of a column for a given application. You should always call application.exists and application.columnExists before using this
 * 
 * @param {number} id Application ID
 * @param {string} column Column name
 * @param {string} value New value to assign to column
 */
exports.setColumnValue = async (id, column, value) => {
    await sql.run("UPDATE `applications` SET ? = ? WHERE `id` = ?", [id, column, value])
}

/**
 * Gets the value of a column for a given application. You should always call application.exists and application.columnExists before using this
 * 
 * @param {number} id Application ID
 * @param {string} column Column name
 */
exports.getColumnValue = async (id, column) => {
    await sql.run("SELECT ? FROM `applications` WHERE `id` = ?", [column, id])
}