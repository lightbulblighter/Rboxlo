const express = require("express")
const exphbs = require("express-handlebars")
const minifier = require("express-minify-html-2")
const layouts = require("handlebars-layouts")
const path = require("path")
const cookieSession = require("cookie-session")
const cookieParser = require("cookie-parser")
const bp = require("body-parser")
const rateLimit = require("express-rate-limit")
const cors = require("cors")
const fileUpload = require("express-fileupload")
const csurf = require("csurf")

const hbh = require(path.join(__dirname, "helpers"))
const util = require(path.join(global.rboxlo.root, "util"))

const error = require(path.join(global.rboxlo.root, "websites", "shared", "lib", "error"))

let app = express()

// Expose some non-sensitive variables to the view engine
app.locals.rboxlo = {
    name: util.titlecase(global.rboxlo.env.NAME),
    version: util.getVersion(),
    domain: `${global.rboxlo.env.SERVER_HTTPS ? "https://" : "http://"}${global.rboxlo.env.SERVER_DOMAIN}`,
    dsr: (global.rboxlo.env.PRODUCTION ? ".min" : ""), // "Debug Static Resource"
    captcha: {
        enabled: global.rboxlo.env.GOOGLE_RECAPTCHA_ENABLED,
        siteKey: global.rboxlo.env.GOOGLE_RECAPTCHA_SITE_KEY
    },
    privacy: {
        lockdown: global.rboxlo.env.PRIVATE_LOCKDOWN,
        registration: global.rboxlo.env.PRIVATE_REGISTRATION,
        referral: global.rboxlo.env.PRIVATE_REFERRAL,
        inviteOnly: global.rboxlo.env.PRIVATE_INVITE_ONLY,
        closed: global.rboxlo.env.PRIVATE_CLOSED
    }
}

// Closed or not?
if (global.rboxlo.env.PRIVATE_CLOSED) {
    app.use((req, res, next) => {
        return res.send("closed")
    })
}

// Set up view engine
let hbs = exphbs.create({ helpers: hbh })

hbs.handlebars.registerHelper(layouts(hbs.handlebars))
hbs.handlebars.registerPartial("partials/layout", "{{prefix}}")

app.engine("handlebars", hbs.engine)
app.set("view engine", "handlebars")
app.set("views", path.join(__dirname, "views"))

// Disable "Express" in X-Powered-By
app.disable("x-powered-by")

// Sessions
app.use(cookieSession({
    name: `${global.rboxlo.env.NAME}_session`,
    keys: [global.rboxlo.env.SERVER_SESSION_SECRET],
    maxAge: (6 * 60 * 60 * 1000) // 6 hours
}))

// Parse requests
app.use(bp.json())
app.use(bp.urlencoded({ extended: true }))
app.use(cookieParser({ secret: global.rboxlo.env.SERVER_COOKIE_SECRET }))

// Files
app.use(cors())

app.use(fileUpload({
    safeFileNames: true,
    preserveExtension: true
}))

// Use our Rboxlo middleware
app.use(require(path.join(__dirname, "middleware")).obj)

// CSRF protection
app.use(csurf({ cookie: true }))
app.use((err, req, res, next) => {
    if (err.code !== "EBADCSRFTOKEN") return next(err)

    console.log("bad csrf")
    return res.sendStatus(403)
    
    // provide no further context
})

// Rate limiting
// NOTE: If you have CloudFlare limits are done automatically
app.use(rateLimit({
    windowMs: (10 * 60 * 1000), // 10 minutes
    max: 100 // 100 requests per 10 minutes
}))

// Minify rendering
app.use(minifier({
    override: true,
    exception_url: false,
    htmlMinifier: {
        removeComments: true,
        collapseWhitespace: true,
        collapseBooleanAttributes: true,
        removeAttributeQuotes: true,
        removeEmptyAttributes: true,
        minifyJS: true
    }
}))

// Static resources (CSS, JavaScript, images, etc.)
app.use(express.static(path.join(__dirname, "public")))

// Constraint
if (global.rboxlo.env.PRIVATE_LOCKDOWN) {
    app.use(require(path.join(__dirname, "routes", "constraint")))
    app.use((req, res, next) => {
        if (req.session.hasOwnProperty("user")) {
            return next()
        } else {
            req.session.rboxlo.redirect = `${req.protocol}://${req.get("host")}${req.originalUrl}`
            return res.redirect("/constraint")
        }
    })
}

// Routes
app.use(require(path.join(__dirname, "routes")))

// Error pages
// These are LAST
app.get("*", error.empty)
app.use(error.catcher)

module.exports.app = app