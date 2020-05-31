// Rboxlo Matchmaker

const express = require("express");
const bodyParser = require("body-parser");
const mysql = require("mysql2");
const config = require("./config.json");

// Connect to database
var connection = mysql.createPool({
    host: config.database.host,
    port: config.database.port,
    user: config.database.user,
    password: config.database.password,
    dataabse: config.database.name
})

// Init express
var app = express.createServer()
app.use(bodyParser.json())

// Matchmaker route
app.post("/arrange", (req, res) => {
    // TODO
})

// Finish
app.listen(config.port);
console.log(`Rboxlo matchmaker listening on port ${config.port}`);