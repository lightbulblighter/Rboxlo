const bp = require("body-parser")
const cookieSession = require("cookie-session")
const cookieParser = require("cookie-parser")
const express = require("express")
const exphbs = require("express-handlebars")
const minifier = require("express-minify-html-2")
const layouts = require("handlebars-layouts")
const path = require("path")
const rateLimit = require("express-rate-limit")
const csurf = require("csurf")

const helpers = require(path.join(__dirname, "helpers"))
const util = require(path.join(global.rboxlo.root, "lib", "base", "util"))
const manifest = require(path.join(global.rboxlo.root, "websites", "manifest"))

const error = require(path.join(global.rboxlo.root, "lib", "error"))

let app = express()
let subdomain = (manifest.tootsie.domain != "INDEX") ? `${manifest.tootsie.domain}.` : ""

// Expose some non-sensitive variables to the view engine
app.locals.rboxlo = {
    name: util.titlecase(global.rboxlo.env.NAME),
    version: util.getVersion(),
    subdomain: subdomain,
    captcha: {
        enabled: global.rboxlo.env.GOOGLE_RECAPTCHA_ENABLED,
        siteKey: global.rboxlo.env.GOOGLE_RECAPTCHA_SITE_KEY
    }
}

// Set up view engine
let hbs = exphbs.create({ helpers: helpers })

hbs.handlebars.registerHelper(layouts(hbs.handlebars))
hbs.handlebars.registerPartial("partials/layout", "{{prefix}}")

app.engine("handlebars", hbs.engine)
app.set("view engine", "handlebars")
app.set("views", path.join(__dirname, "views"))

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

// CSRF protection
app.use(csurf({ cookie: true }))
app.use((err, req, res, next) => {
    if (err.code !== "EBADCSRFTOKEN") return next(err)

    return res.sendStatus(403)
    // provide no further context
})

// Use our Rboxlo middleware
app.use(require(path.join(__dirname, "middleware")).obj)

// Rate limiting
// NOTE: If you have CloudFlare limits are done automatically
app.use(rateLimit({
    windowMs: (10 * 60 * 1000), // 10 minutes
    max: 100 // 100 requests per 10 minutes
}))

// Routes
app.use(require(path.join(__dirname, "routes")))

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

// Error pages
// These are LAST
app.get("*", error.empty)
app.use(error.catcher)

module.exports.app = app