var exports = module.exports = {}

const fs = require("fs")
const moment = require("moment")
const path = require("path")
const validator = require("validator")
const xss = require("xss")

/**
 * Converts a string to titlecase ("a super Duper cool Movie" -> "A Super Duper Cool Movie")
 * 
 * @param {string} text Text to titlecase
 * 
 * @returns {string} Titlecase-d text
 */
exports.titlecase = (text) => {
    text = text.toLowerCase().split(" ")
    for (var i = 0; i < text.length; i++) {
        text[i] = text[i].charAt(0).toUpperCase() + text[i].slice(1)
    }

    return text.join(" ")
}

/**
 * Synchronously reads a files data in utf8 and returns its contents
 * 
 * @param {string} path Exact path to file
 * 
 * @returns {string} Data of file at given path
 */
exports.readFile = (path) => {
    return fs.readFileSync(path, { encoding: "utf8", flag: "r" })
}

/**
 * Gets and returns the current application version in accordance with packaging set at build-time
 * 
 * @returns {string} Returns the full application version in proper form
 */
exports.getVersion = () => {
    let out = {
        semver: global.rboxlo.env.VERSION
    }

    if (fs.existsSync(path.join(global.rboxlo.root, "commit"))) {
        out.commit = exports.readFile(path.join(global.rboxlo.root, "commit")).replace(/^\s+|\s+$/g, "")
    }

    return out
}

/**
 * Validates and then normalizes an E-Mail address
 * 
 * @param {string} address Address to normalize
 * @param {boolean} stripSubAddress Whether to strip the "subaddress"-- e.g. the "+rboxlo" in "brighteyes+rboxlo@gmail.com"
 * 
 * @returns {(boolean|string)} Returns FALSE if it was not a valid E-Mail address, or the normalized E-Mail address
 */
exports.filterEmail = (address, stripSubAddress = true) => {
    address = address.trim()

    if (!validator.isEmail(address)) {
        return false
    }

    if (!stripSubAddress) {
        address = validator.normalizeEmail(address, {
            gmail_remove_subaddress: false,
            icloud_remove_subaddress: false,
            yahoo_remove_subaddress: false,
            outlookdotcom_remove_subaddress: false
        })
    } else {
        address = validator.normalizeEmail(address)
    }

    return address
}

/**
 * Changes snake_case to camelCase
 * 
 * @param {string} string String to make camelCase
 * 
 * @returns {string} camelCased string
 */
exports.snakeCaseToCamelCase = (string) => {
    let split = string.split("_")
    let result = []

    for (var i = 1; i < split.length; i++) {
        let shrapnel = split[i].toLowerCase()
        result.push((shrapnel.charAt(0).toUpperCase()) + shrapnel.slice(1))
    }

    return (split[0].toLowerCase() + result.join(""))
}

/**
 * Normalizes an array of snake_case keys to camelCase
 * 
 * @param {array} array Array to normalize
 * 
 * @returns {array} Normalized array
 */
exports.snakeCaseToCamelCaseArray = (array) => {
    let result = {}

    for (const [key, value] of Object.entries(array)) {
        result[exports.snakeCaseToCamelCase(key)] = value
    }

    return result
}

/**
 * Normalizes a nested array of snake_case keys to camelCase
 * 
 * @param {array} array Array to normalize
 * 
 * @returns {array} Normalized array
 */
exports.snakeCaseToCamelCaseArrayNested = (array) => {
    let result = []

    for (let i = 0; i < array.length; i++) {
        result.push(exports.snakeCaseToCamelCaseArray(array[i]))
    }

    return result
}

/**
 * Gets a resource's URL from its short path, i.e. "styles.css" -> "https://rboxlo.loc/css/styles.min.css"
 * 
 * @param {string} name File name of resource
 * @param {boolean} getResourceFolder Whether to get the specific folder of the resource (default: TRUE)
 * 
 * @returns {string} Formatted path to resource
 */
exports.resourceInternal = (name, getResourceFolder = true) => {
    if (!getResourceFolder) {
        return `/${name}`
    }

    let split = name.split(".")
    let types = {
        "img": ["png", "svg", "jpg", "tiff"],
        "css": ["css"],
        "js": ["js"]
    }

    let folder
    for (const [key] of Object.entries(types)) {
        if (types[key].includes(split[1])) {
            folder = key
        }
    }

    let dsr = (global.rboxlo.env.PRODUCTION ? ".min" : "")
    let path = `/${folder}/${split[0]}${dsr}.${split[1]}`

    return path
}

/**
 * Formats a unix timestamp into a human readable form, i.e. 1625541170 -> "07-05-2021 @ 10:13 P.M."
 * 
 * @param {number} unix Unix timestamp
 * 
 * @returns {string} Formatted timestamp
 */
exports.timestamp = (unix) => {
    return moment.unix(unix).format("L @ h:mm A")
}

/**
 * Safely escapes a string into an XSS-safe form, i.e. "<hi>!" -> "&lt;hi&rt;!"
 * 
 * @param {string} text Text to escape
 * 
 * @returns {string} XSS-safe string
 */
exports.xss = (text) => {
    return xss(text)
}

/**
 * Gets a string from another string
 * 
 * @param {string} str Source string
 * @param {number} start Character position to start at
 * @param {number} end Character position to end at
 * 
 * @returns {string} Sub string
 */
exports.substr = (str, start, end) => {
    return str.substring(start, end)
}