const express = require("express")
const path = require("path")
const vhost = require("vhost")

const manifest = require(path.join(__dirname, "websites", "manifest"))
const util = require(path.join(__dirname, "lib", "base", "util"))

const magic = "INDEX"

global.rboxlo = {}

let app = express()
let hosting = []

if (!process.env.DOCKER) {
    console.log("Not running in Docker, exiting...")
    process.exit(1)
}

// Set environment variables to a table named global.rboxlo.env
// These are separate from process.env as they are parsed for booleans, and are cached; accessing process.env directly blocks for each call
global.rboxlo.resetEnvironmentCache = () => {
    global.rboxlo.env = {}
    let keys = Object.keys(process.env)
    
    for (let i = 0; i < keys.length; i++) {
        let value = process.env[keys[i]]
        
        if (value.toLowerCase() == "false") {
            value = false
        } else if (value.toLowerCase() == "true") {
            value = true
        }
        
        global.rboxlo.env[keys[i]] = value
    }
}
global.rboxlo.resetEnvironmentCache()

// Set root
global.rboxlo.root = __dirname

// Set titlecased name
global.rboxlo.name = util.titlecase(global.rboxlo.env.NAME)

// Set Node debugging variables
process.env.NODE_ENV = (global.rboxlo.env.PRODUCTION ? "production" : "development")
if (!global.rboxlo.env.PRODUCTION) process.env.DEBUG = "express:*"

// Autoload websites
for (const [name, website] of Object.entries(manifest)) {
    let domains = []

    if (Array.isArray(website.domain)) {
        for (let i = 0; i < website.domain.length; i++) {
            domains.push(website.domain[i] === magic ? "" : website.domain[i].toLowerCase())
        }
    } else {
        domains.push(website.domain === magic ? "" : website.domain.toLowerCase())
    }
    
    for (let i = 0; i < domains.length; i++) {
        let domain = domains[i]

        if (hosting.includes(domain)) {
            throw `Duplicate vhost was found for website "${name}", vhost was "${domain}"`
        }

        hosting.push(domain)
        app.use(vhost(`${domain}${domain == "" ? "" : "."}${global.rboxlo.env.SERVER_DOMAIN}`, require(path.join(__dirname, website.entrypoint)).app))
    }
}

// Autoload services

// Boot everything up
// 1: Website master server
app.listen(global.rboxlo.env.SERVER_PORT, () => {
    console.log(`Running ${global.rboxlo.name} on port ${global.rboxlo.env.SERVER_PORT}`)
})

// 2: Services (Okra, etc.)
// TODO