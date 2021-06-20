var exports = module.exports = {}

const path = require("path")

const sql = require(path.join(global.rboxlo.root, "sql"))

/**
 * Checks if a given application exists
 * 
 * @param {string} name Application name
 * @returns {boolean} If the application exists
 */
exports.exists = async (name) => {
    let result = (await sql.run("SELECT 1 FROM `applications` WHERE `name` = ?", name))

    return !(result.length == 0)
}

/**
 * Returns an array of application names
 * 
 * @returns {array} Application names
 */
exports.fetchAll = async () => {
    let result = (await sql.run("SELECT `name` FROM `applications`"))

    return result
}