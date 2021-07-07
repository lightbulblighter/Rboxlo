const moment = require("moment")

const path = require("path")

const util = require(path.join(global.rboxlo.root, "lib", "base", "util"))

async function middleware(req, res) {
    req.rboxlo = {}

    if (!req.session.hasOwnProperty("rboxlo")) {
        req.session.rboxlo = {}
    }
    
    req.session.rboxlo.bust = moment().unix() // resets contents each page req

    // Get IP and store in req.rboxlo.ip
    let realip = req.connection.remoteAddress.trim()
    if (realip.startsWith("::ffff:")) realip = realip.slice(7)

    if (req.headers.hasOwnProperty("http_cf_connecting_ip") && global.rboxlo.env.SERVER_CLOUDFLARE) {
        let cfip = req.headers["http_cf_connecting_ip"].trim()
        req.rboxlo.ip = ((realip != cfip) ? cfip : realip)
    } else {
        req.rboxlo.ip = realip
    }

    // Session security
    if (!req.session.rboxlo.hasOwnProperty("ip")) {
        req.session.rboxlo.ip = req.rboxlo.ip
    } else {
        if (req.session.rboxlo.ip !== req.rboxlo.ip) {
            // Clear the session if different IP
            req.session.rboxlo = {}
            req.session.rboxlo.ip = req.rboxlo.ip
        }
    }
    
    if (!res.locals.session) {
        res.locals.session = { csrf: req.csrfToken() }
    } else {
        res.locals.session.csrf = req.csrfToken()
    }
}

// Don't modify this
module.exports = {obj: ((req, res, next) => {
    middleware(req, res)
    next()
})}