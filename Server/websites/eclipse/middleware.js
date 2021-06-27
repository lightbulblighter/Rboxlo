const moment = require("moment")

const path = require("path")

const session = require(path.join(__dirname, "lib", "session"))
const util = require(path.join(global.rboxlo.root, "util"))
const user = require(path.join(__dirname, "lib", "user"))

async function middleware(req, res) {
    req.rboxlo = {}

    if (!req.session.hasOwnProperty("rboxlo")) {
        req.session.rboxlo = {}
    }
    
    req.session.rboxlo.bust = moment().unix() // resets contents each page req

    //
    // X-Powered-By header
    // The string "Rboxlo" here is hardcoded in the form of a ASCII charcode array. Why?
    //
    // 1. Given the audience of Rboxlo, most edits scrubbing Rboxlo off of their private server will be through
    //    complete search and replaces, rather than simply editing the environment file though that achieves the
    //    same effect. Hardcoding it as an ASCII charcode array will hopefully deter that.
    //
    // 2. Rboxlo is an application that powers private servers. It is not a private server in itself. It is important
    //    to retain the fact that it powers private servers.
    //
    if (global.rboxlo.env.SERVER_X_POWERED_BY) {
        let poweredBy = [ 82, 98, 111, 120, 108, 111 ] // Literal "Rboxlo"
        
        res.setHeader("X-Powered-By", `${String.fromCharCode.apply(null, poweredBy)}/${util.getVersion().semver}`)
    }

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
        if (!(await user.exists(req.session.rboxlo.user.id))) {
            session.clear(req)
        }
    }

    // Non-sensitive session details for view engine
    // FYI: Session NEVER gets changed besides at registration and sign-in, so we only do this once (or at least try to-- these are set once per request)
    if (req.session.rboxlo.user && !res.locals.session) {
        res.locals.session = {
            id: req.session.rboxlo.user.id,
            username: req.session.rboxlo.user.username,
            permissions: req.session.rboxlo.user.permissions
        }
    }
}

// Don't modify this
module.exports = {obj: ((req, res, next) => {
    middleware(req, res)
    next()
})}