var exports = module.exports = {}

/**
 * Checks if a user is authenticated for routes, if not, redirects to login page
 */
 exports.authenticated = (req, res, next) => {
    if (req.session.rboxlo.hasOwnProperty("user")) {
        return next()
    }

    req.session.rboxlo.redirect = `${req.protocol}://${req.get("host")}${req.originalUrl}`
    res.redirect("/login")
}