var exports = module.exports = {}

let manifest = [
    {
        code: 400,
        blob: "sweaty",
        description: "The server could not understand the request."
    },
    {
        code: 403,
        blob: "dead",
        description: "You do not have the authorization for the requested resource."
    },
    {
        code: 404,
        blob: "ghastly",
        description: "The requested resource could not be found."
    },
    {
        code: 405,
        blob: "sleeping",
        description: "The method for this resource is not implemented or is not allowed."
    },
    {
        code: 429,
        blob: "exhausted",
        description: "You are sending too many requests."
    },
    {
        code: 500,
        blob: "scared",
        description: "An internal server error has occurred."
    },
    {
        code: 502,
        blob: "inquisitve",
        description: "The server, while working as a gateway to get a response needed to handle the request, got an invalid response."
    },
    {
        code: 503,
        blob: "sick",
        description: "The server is not ready to handle the request at this time."
    }
]

let emptyError = manifest.find(e => e.code === 404)

exports.empty = (req, res) => {
    res.status(emptyError.code)

    if (req.accepts("html")) {
        res.render("error", { objects: emptyError, layout: "error", title: emptyError.code })
        return
    }

    if (req.accepts("json")) {
        res.json({ success: false, statusCode: emptyError.code })
        return
    }

    res.type("txt").send(emptyError.code)
}

exports.catcher = (err, req, res, next) => {
    let error = manifest.find(e => e.code === err.status)
    if (error) {
        res.status(error.code)

        if (req.accepts("html")) {
            res.render("error", { objects: error, layout: "error", title: error.code })
            return
        }
    
        if (req.accepts("json")) {
            res.json({ success: false, statusCode: error.code })
            return
        }
    
        res.type("txt").send(error.code)
        return
    } else {
        if (req.accepts("json")) {
            res.status(err.status)

            if (req.accepts("json")) {
                res.json({ success: false, statusCode: err.status })
                return
            }

            res.type("txt").send(err.status)
            return
        }
    }
}