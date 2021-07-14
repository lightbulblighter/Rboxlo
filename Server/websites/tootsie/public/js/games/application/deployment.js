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
            $(data).each(function () {
                let container = $("<tr>", {
                    "data-rboxlo-application-id": this.id,
                    style: "cursor: pointer"
                })

                let id = $("<td>", { class: "align-middle text-center" }).append(this.id)
                let displayName = $("<td>", { class: "align-middle"}).append(this.displayName)
                let internalName = $("<td>", { class: "align-middle" }).append($("<code>").append(this.internalName))
                let uuid = $("<td>", { class: "align-middle" }).append($("<code>").append(this.uuid))
                let lastDeployedVersionUUID = $("<td>", { class: "align-middle" })

                if (this.lastDeployedVersionUUID.length > 0) {
                    lastDeployedVersionUUID.append($("<code>").append(this.lastDeployedVersionUUID))
                } else {
                    lastDeployedVersionUUID.append($("<i>", { class: "text-muted" }).append("none"))
                }

                let lastUpdatedTimestamp = $("<td>", { class: "align-middle" }).append(timestamp(this.lastUpdatedTimestamp))
                let createdTimestamp = $("<td>", { class: "align-middle" }).append(timestamp(this.createdTimestamp))
                
                container.append(id)
                container.append(displayName)
                container.append(internalName)
                container.append(uuid)
                container.append(lastDeployedVersionUUID)
                container.append(lastUpdatedTimestamp)
                container.append(createdTimestamp)

                body.append(container)

                container.on("click", () => {
                    window.location.href = url(`/games/application/deployment?id=${this.id}`)
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