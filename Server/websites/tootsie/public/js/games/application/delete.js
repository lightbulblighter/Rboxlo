function deleteApplication(id) {
    console.log(`[rboxlo->info]: delete::deleteApplication | deleting application with ID ${id}`)

    let csrf = $('meta[name="rboxlo-csrf"]').attr("content")

    fetch(url("/api/games/application/delete"), {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            _csrf: csrf,
            id: id
        })
    })
    .then(res => res.json())
    .then(data => {
        let alert = $("#update")

        if (data.success) {
            alert.text(`Successfully deleted application "${data.deleted.displayName}"!`)
            alert.removeClass("d-none")
            alert.removeAttr("style")

            $("#delete-application-modal").modal("hide")

            populate()

            setTimeout(() => {
                alert.fadeOut("slow")
            }, 2250)
        }
    })
}

function modal(id) {
    $("#delete-application-submit").off("click").on("click", () => { deleteApplication(id) })
    $("#delete-application-modal").modal("show")
}

function populate(initializing = false) {
    console.log(`[rboxlo->info]: delete::data | fetching data (initializing: ${initializing ? "TRUE" : "FALSE"})`)
    
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
                let container = $("<tr>", { style: "cursor: pointer" })

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
                    modal(this.id)
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