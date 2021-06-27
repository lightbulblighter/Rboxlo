var exports = module.exports = {}

/**
 * Clears a session
 */
exports.clear = (req) => {
    req.session.rboxlo.ip = req.rboxlo.ip
    req.session.rboxlo = {}
}