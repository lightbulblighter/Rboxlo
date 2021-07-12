var exports = module.exports = {}

const bytes = require("bytes")
const moment = require("moment")
const path = require("path")
const uuid = require("uuid")

const sql = require(path.join(__dirname, "base", "sql"))

/**
 * Maximum application download size, in bytes
 */
const MAX_APPLICATION_SIZE = bytes(global.rboxlo.env.MAX_APPLICATION_SIZE)
exports.MAX_APPLICATION_SIZE = MAX_APPLICATION_SIZE

/**
 * Maximum launcher file size, in bytes
 */
const MAX_LAUNCHER_SIZE = bytes(global.rboxlo.env.MAX_LAUNCHER_SIZE)
exports.MAX_LAUNCHER_SIZE = MAX_LAUNCHER_SIZE

/**
 * Maximum arbiter file size, in bytes
 */
const MAX_ARBITER_SIZE = bytes(global.rboxlo.env.MAX_ARBITER_SIZE)
exports.MAX_ARBITER_SIZE = MAX_ARBITER_SIZE

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
    // FIXME: Enable this once we migrate to MariaDB
    // return (await sql.run("SHOW COLUMNS FROM `applications` LIKE ?", column)).length > 0
    
    // Keep in line with schema
    let columns = [
        "id",
        "uuid",
        "created_timestamp",
        "last_updated_timestamp",
        "last_deployed_version_uuid",
        "internal_name",
        "display_name"
    ]

    return columns.includes(column)
}

/**
 * Sets the value of a column for a given application. You should always call application.exists before using this
 * 
 * @param {number} id Application ID
 * @param {string} column Column name
 * @param {string} value New value to assign to column
 * 
 * @returns {(boolean|undefined)} Returns FALSE if the column doesn't exist, otherwise does not have a return value by default
 */
exports.setColumnValue = async (id, column, value) => {
    if (!await exports.columnExists(column)) {
        return false
    }

    // SECURITY: Escaping SQL without prepares.
    await sql.run(`UPDATE \`applications\` SET ${column} = ? WHERE \`id\` = ?`, [value, id])
}

/**
 * Gets the value of a column for a given application. You should always call application.exists before using this
 * 
 * @param {number} id Application ID
 * @param {string} column Column name
 * 
 * @returns {(boolean|undefined)} Returns FALSE if the column doesn't exist, otherwise does not have a return value by default
 */
exports.getColumnValue = async (id, column) => {
    if (!await exports.columnExists(column)) {
        return false
    }

    // SECURITY: Escaping SQL without prepares.
    await sql.run(`SELECT ${column} FROM \`applications\` WHERE \`id\` = ?`, id)
}