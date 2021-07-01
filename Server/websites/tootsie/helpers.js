var exports = module.exports = {}

const moment = require("moment")

exports.unix2timestamp = (unix) => {
    return moment.unix(unix).format("L @ h:mm A")
}