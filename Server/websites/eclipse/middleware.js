const moment = require("moment")

const path = require("path")

const session = require(path.join(global.rboxlo.root, "lib", "session"))
const user = require(path.join(global.rboxlo.root, "lib", "user"))

async function middleware(req, res) {
    req.rboxlo = {}

    if (!req.session.hasOwnProperty("rboxlo")) {
        req.session.rboxlo = {}
    }
    
    req.session.rboxlo.bust = moment().unix() // resets session contents each page req

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
            session.clear(req)
        }
    }
    
    // Remember me
    if (req.cookies.remember_me && !req.session.rboxlo.user) {
        let verified = await user.verifyLongTermSession(req.cookies.remember_me)

        if (verified !== false) {
            // login to user
            let info = await user.getNecessarySessionInfoForUser(verified)
            req.session.rboxlo.user = info
        } else {
            // crumbs of a cookie, sweep it up and get rid of it
            res.clearCookie("remember_me")
        }
    }

    // kill session if user does not exist
    if (req.session.rboxlo.hasOwnProperty("user") && req.session.rboxlo.user.length > 0) {
        if (!await user.exists(req.session.rboxlo.user.id)) {
            session.clear(req)
        }
    }

    if (!res.locals.session) {
        res.locals.session = { csrf: req.csrfToken() }
    } else {
        res.locals.session.csrf = req.csrfToken()
    }

    if (req.session.rboxlo.hasOwnProperty("user") && !res.locals.session.hasOwnProperty("user")) {
        res.locals.session.user = {
            id: req.session.rboxlo.user.id,
            username: req.session.rboxlo.user.username,
            permissions: req.session.rboxlo.user.permissions
        }
    }

    // Remove locals if no session
    if (res.locals.session.hasOwnProperty("user") && !req.session.rboxlo.hasOwnProperty("user")) {
        delete res.locals.session.user 
    }
}

// Don't modify this
module.exports = {obj: ((req, res, next) => {
    middleware(req, res)
    next()
})}