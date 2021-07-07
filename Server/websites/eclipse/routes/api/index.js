var router = require("express").Router()

router.get("/ok", (req, res) => {
    return res.json({ sucess: true })
})

module.exports = router