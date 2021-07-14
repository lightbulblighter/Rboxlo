var exports = module.exports = {}

const mariadb = require("mariadb")

const pool = mariadb.createPool({
    host: "database",
    port: 3306,
    database: global.rboxlo.env.DB_NAME,
    user: global.rboxlo.env.DB_USERNAME,
    password: global.rboxlo.env.DB_PASSWORD,
    connectionLimit: 10
})

/**
 * Runs a SQL query
 * 
 * @param {string} query SQL query to run
 * @param {*} parameters Parameters to prepare with, can be a singular version of anything or an array
 * @param {function} net Anonymous function to catch errors with (a "net"), with the error as the first parameter. This is optional.
 * 
 * @returns {array} Array containing SQL results
 */
exports.run = async (query, parameters = false, net = false) => {
    if (net === false) {
        net = (e) => { throw e }
    }

    var results

    try {
        let connection = await pool.getConnection()

        if (parameters != false) {
            results = await connection.query(query, parameters)
        } else {
            results = await connection.query(query, parameters)
        }

        connection.end()
    } catch (e) {
        net(e)
    }

    
    return results
}

exports.pool = pool