const path = require("path")

var exports = module.exports = { ... require(path.join(global.rboxlo.root, "lib", "base", "util")) }

/**
 * Gets a complete URL from a path, i.e. "/path/to/page" -> "https://rboxlo.com/path/to/page"
 * 
 * @param {string} path Path to combine with domain
 * 
 * @returns {string} Complete URL
 */
exports.url = (path) => {
    let schema = "http" + (global.rboxlo.env.SERVER_HTPS ? "s" : "")
    return `${schema}://${global.rboxlo.env.SERVER_DOMAIN}${path}`
}

/**
 * Gets a resource's URL from its short path, i.e. "styles.css" -> "https://rboxlo.loc/css/styles.min.css"
 * 
 * @param {string} name File name of resource
 * @param {boolean} getResourceFolder Whether to get the specific folder of the resource (default: TRUE)
 * 
 * @returns {string} Formatted path to resource
 */
exports.resource = (name, getResourceFolder = true) => {
    return exports.url(exports.resourceInternal(name, getResourceFolder))
}