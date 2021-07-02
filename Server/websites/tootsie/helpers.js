var exports = module.exports = {}

const moment = require("moment")
const xss = require("xss")

exports.unix2timestamp = (unix) => {
    return moment.unix(unix).format("L @ h:mm A")
}

exports.xss = (text) => {
    return xss(text)
}