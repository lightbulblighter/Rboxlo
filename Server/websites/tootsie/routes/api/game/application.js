var router = require("express").Router()

const path = require("path")
const uuid = require("uuid")

const application = require(path.join(global.rboxlo.root, "lib", "application"))
const user = require(path.join(global.rboxlo.root, "lib", "user"))

router.get("/json", user.authenticated, async (req, res) => {
    return res.json(await application.all())
})

router.post("/uuid", user.authenticated, async (req, res) => {
    if (req.body.hasOwnProperty("id") && !isNaN(req.body.id) && validator.isInt(req.body.id)) {
        let id = parseInt(req.body.id)

        if (await application.exists(id)) {
            switch (req.body.method) {
                case "regenerate":
                    let oldUUID = (await application.getUUID(id))
                    let newUUID = uuid.v4()

                    await application.setColumnValue(id, "uuid", newUUID)
                    await application.updateLastUpdatedTimestamp(id)

                    return res.json({
                        success: true,
                        id: id,
                        old: oldUUID,
                        new: newUUID
                    })
                default:
                    return res.json({ success: false })
            }
        }
    }

    return res.json({ sucess: false })
})

router.post("/update", user.authenticated, async (req, res) => {
    if (req.body.hasOwnProperty("id") && !isNaN(req.body.id) && validator.isInt(req.body.id) && req.body.hasOwnProperty("column") && req.body.hasOwnProperty("value") && req.body.column.length > 0 && req.body.value.length > 0) {
        let id = parseInt(req.body.id)
        
        if (await application.exists(id)) {
            if (await application.columnExists(req.body.column)) {
                await application.setColumn(id, req.body.column, req.body.value)
                await application.updateLastUpdatedTimestamp(id)

                return res.json({ success: true, id: id })
            }
        }
    }

    return res.json({ success: false })
})

router.post("/delete", user.authenticated, async (req, res) => {
    if (req.body.hasOwnProperty("id") && !isNaN(req.body.id) && validator.isInt(req.body.id)) {
        let id = parseInt(req.body.id)

        if (await application.exists(id)) {
            await application.delete(id)

            return res.json({ sucess: true, id: id })
        }
    }
    
    return res.json({ sucess: false })
})

module.exports = router