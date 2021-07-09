var exports = module.exports = {}

/**
 * Clears a session
 */
exports.clear = (req) => {
    req.session.rboxlo = {}
    req.session.rboxlo.ip = req.rboxlo.ip
}