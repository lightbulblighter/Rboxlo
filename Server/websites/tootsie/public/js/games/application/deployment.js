function populate(initializing = false) {
    console.log(`[rboxlo->info]: deployment::data | fetching data (initializing: ${initializing ? "TRUE" : "FALSE"})`)

    if (!initializing) {
        $("#data").addClass("d-none")
        $("#data-loading").removeClass("d-none")
    }
    
    let body = $("#data-body")
    body.html("")

    if (!($("#data-empty").hasClass("d-none"))) {
        $("#data-empty").addClass("d-none")
    }

    fetch(url("/api/games/application/json"))
    .then(res => res.json())
    .then(data => {
        if (data.length == 0) {
            $("#data-loading").addClass("d-none")
            $("#data-empty").removeClass("d-none")
        } else {
            $(data).each((_, app) => {
                let container = $("<tr>", {
                    "data-rboxlo-application-id": app.id,
                    style: "cursor: pointer"
                })

                let id = $("<td>", { class: "align-middle text-center" }).append(app.id)
                let displayName = $("<td>", { class: "align-middle"}).append(app.displayName)
                let internalName = $("<td>", { class: "align-middle" }).append($("<code>").append(app.internalName))
                let uuid = $("<td>", { class: "align-middle" }).append($("<code>").append(app.uuid))
                let lastDeployedVersionUUID = $("<td>", { class: "align-middle" })

                if (app.lastDeployedVersionUUID.length > 0) {
                    lastDeployedVersionUUID.append($("<code>").append(app.lastDeployedVersionUUID))
                } else {
                    lastDeployedVersionUUID.append($("<i>", { class: "text-muted" }).append("none"))
                }

                let lastUpdatedTimestamp = $("<td>", { class: "align-middle" }).append(timestamp(app.lastUpdatedTimestamp))
                let createdTimestamp = $("<td>", { class: "align-middle" }).append(timestamp(app.createdTimestamp))
                
                container.append(id)
                container.append(displayName)
                container.append(internalName)
                container.append(uuid)
                container.append(lastDeployedVersionUUID)
                container.append(lastUpdatedTimestamp)
                container.append(createdTimestamp)

                body.append(container)

                container.on("click", () => {
                    window.location.href = url(`/games/application/deployment?id=${app.id}`)
                })
            })

            setTimeout(() => {
                $("#data-loading").addClass("d-none")
                $("#data").removeClass("d-none")
            }, 200)
        }
    })
}

$(document).ready(() => {
    $("#data-refresh").on("click", () => { populate() })
    populate(true)
})