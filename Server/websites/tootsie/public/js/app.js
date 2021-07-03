window.rboxlo = []

function unix2timestamp(unix) {
    return moment.unix(unix).format("L @ h:mm A")
}

// Document init
$(document).ready(() => {
    let links = $("[laid]")
    links.each((_, link) => {
        link = $(link)
        if (link.attr("laid") == window.rboxlo.laid) {
            link.addClass("active")
            link.find("a").addClass("text-white")
        }
    })

    if ($("#tabination").length) {
        $("#tabination a").click(function(e){
            e.preventDefault();
            $(this).tab('show');
        });
    }
})

// Copying
function copy(id) {
    let text = $(`#${id}`).text()

    if (!navigator.clipboard || !window.isSecureContext) {
        let area = $("<textarea>", {
            style: "position: fixed; top: 0; width: 2em; height: 2em; padding: 0; border: none; outline: none; boxShadow: none; background: transparent; display: none;"
        })

        area.append(text)

        $(document.body).append(area)

        area.focus()
        area.select()

        try {
            document.execCommand("copy")
        } catch (err) {
            console.log(`failed to copy text ${text} because ${err}`)
        }

        area.remove()
    } else {
        navigator.clipboard.writeText(text)
    }
}

// Modify Application
function fetchApplicationDataModify(firstTime = false) {
    if (!firstTime) {
        $("#data").addClass("d-none")
        $("#data-loading").removeClass("d-none")
    }
    
    let body = $("#data-body")
    body.html("")

    if (!($("#data-empty").hasClass("d-none"))) {
        $("#data-empty").addClass("d-none")
    }

    fetch(`${window.rboxlo.domain}/games/application/json`)
        .then(res => res.json())
        .then(data => {
            if (data.length == 0) {
                $("#data-loading").addClass("d-none")
                $("#data-empty").removeClass("d-none")
            } else {
                $(data).each((_, app) => {
                    let container = $("<tr>", { "data-rboxlo-application-id": app.id })
                    
                    let edit = $("<td>", {
                        class: "align-middle text-center table-button",
                        scope: "row"
                    }).append($("<a>", {
                        href: `${window.rboxlo.domain}/games/application/modify?id=${app.id}`
                    }).append($("<button>", {
                        class: "btn btn-primary btn-sm",
                        type: "button"
                    }).append($("<i>", {
                        class: "fas fa-pencil"
                    }))))

                    let trash = $("<td>", {
                        class: "align-middle text-center table-button",
                        scope: "row"
                    }).append($("<button>", {
                        class: "btn btn-danger btn-sm",
                        type: "button",
                        onclick: `window.rboxlo.currentlyDeleting=${app.id}`,
                        "data-bs-toggle": "modal",
                        "data-bs-target": "#deleteApplicationModal"
                    }).append($("<i>", {
                        class: "fas fa-trash"
                    })))

                    let deploy = $("<td>", {
                        class: "align-middle text-center table-button",
                        scope: "row"
                    }).append($("<a>", {
                        class: "btn btn-success btn-sm text-white",
                        type: "button",
                        href: `${window.rboxlo.domain}/games/application/deployment?id=${app.id}`
                    }).append($("<i>", {
                        class: "fas fa-upload"
                    })))

                    let id = $("<td>", {
                        class: "align-middle text-center"
                    }).append(app.id)

                    let uuid = $("<td>", {
                        class: "align-middle"
                    }).append($("<div>", {
                        class: "d-flex justify-content-between align-items-center"
                    }).append([
                        $("<code>", { id: `data-uuid-${app.id}` }).append(app.uuid),
                        $("<button>", { class: "btn btn-secondary btn-sm", onclick: `copy('data-uuid-${app.id}')` }).append($("<i>", { class: "fas fa-clipboard" }))
                    ]))

                    let lastVersionUUID = $("<td>", {
                        class: "align-middle"
                    })
                    
                    if (app.last_deployed_version_uuid.length > 0) {
                        lastVersionUUID.append($("<div>", {
                            class: "d-flex justify-content-between align-items-center"
                        }).append([
                            $("<code>", { id: `data-lastVersionUUID-${app.id}` }).append(app.last_deployed_version_uuid),
                            $("<button>", { class: "btn btn-secondary btn-sm", onclick: `copy('data-lastVersionUUID-${app.id}')` }).append($("<i>", { class: "fas fa-clipboard" }))
                        ]))
                    } else {
                        lastVersionUUID.append($("<div>", {
                            class: "d-flex justify-content-between align-items-center"
                        }).append([
                            $("<i>", { class: "text-muted" }).append("none"),
                            $("<button>", { class: "btn btn-secondary btn-sm", disabled: "" }).append($("<i>", { class: "fas fa-clipboard" }))
                        ]))
                    }

                    let internalName = $("<td>", {
                        class: "align-middle"
                    }).append($("<div>", {
                        class: "d-flex justify-content-between align-items-center"
                    }).append([
                        $("<code>", { id: `data-internalName-${app.id}` }).append(app.internal_name),
                        $("<button>", { class: "btn btn-secondary btn-sm", onclick: `copy('data-internalName-${app.id}')` }).append($("<i>", { class: "fas fa-clipboard" }))
                    ]))

                    let displayName = $("<td>", { class: "align-middle"}).append(app.display_name)
                    let lastUpdatedTimestamp = $("<td>", { class: "align-middle" }).append(unix2timestamp(app.last_updated_timestamp))
                    let createdTimestamp = $("<td>", { class: "align-middle" }).append(unix2timestamp(app.created_timestamp))

                    container.append(edit)
                    container.append(trash)
                    container.append(deploy)
                    container.append(id)
                    container.append(uuid)
                    container.append(lastVersionUUID)
                    container.append(internalName)
                    container.append(displayName)
                    container.append(lastUpdatedTimestamp)
                    container.append(createdTimestamp)

                    body.append(container)
                })

                setTimeout(() => {
                    $("#data-loading").addClass("d-none")
                    $("#data").removeClass("d-none")
                }, 200)
            }
        })
}

function regenUUIDModify() {
    let spinner = $("#spinner-uuid")
    spinner.removeClass("d-none")
    
    let id = $('meta[name="rboxlo-application-id"]').attr("content")
    let csrf = $('meta[name="rboxlo-csrf"]').attr("content")

    fetch(`${window.rboxlo.domain}/games/application/regen-uuid`, {
        method: "POST",
        body: new URLSearchParams({
            "id": id,
            "_csrf": csrf
        })
    })
    .then(res => res.json())
    .then(data => {
        spinner.addClass("d-none")
        let update = $("#modification-update")

        if (data.success) {
            if (update.hasClass("alert-danger")) {
                update.removeClass("alert-danger")
                update.addClass("alert-success")
            }

            update.text("Successfully regenerated application UUID!")
            update.removeClass("d-none")
            update.removeAttr("style")

            $("#uuid").text(data.newUUID)
            $("#lastUpdated").text(unix2timestamp(moment().unix()))
        } else {
            if (update.hasClass("alert-success")) {
                update.removeClass("alert-success")
                update.addClass("alert-danger")
            }
            
            update.text("Unknown error occurred")
            update.removeClass("d-none")
            update.removeAttr("style")
        }

        setTimeout(() => {
            update.fadeOut("slow")
        }, 3250)
    })
}

function saveEditDisplayNameChanges() {
    let spinner = $("#spinner-editDisplayNameChanges")
    let modal = $("#editDisplayNameModal")
    let modalText = $("#displayNameModalText")
    let error = $("#internalNameModalError")
    let parentText = $("#displayName")
    let parentAlert = $("#modification-update")

    if (!error.hasClass("d-none")) {
        error.addClass("d-none")
    }

    spinner.removeClass("d-none")

    let id = $('meta[name="rboxlo-application-id"]').attr("content")
    let csrf = $('meta[name="rboxlo-csrf"]').attr("content")
    let input = $("#editDisplayNameInput")

    if ((input.val()).length == 0) {
        if (error.hasClass("alert-success")) {
            error.removeClass("alert-success")
            error.addClass("alert-danger")
        }

        error.text("The new display name must not be empty.")
        error.removeClass("d-none")

        return
    }

    fetch(`${window.rboxlo.domain}/games/application/update-display-name`, {
        method: "POST",
        body: new URLSearchParams({
            "id": id,
            "_csrf": csrf,
            "name": input.val()
        })
    })
    .then(res => res.json())
    .then(data => {
        spinner.addClass("d-none")

        if (data.success) {
            if (parentAlert.hasClass("alert-danger")) {
                parentAlert.removeClass("alert-danger")
                parentAlert.addClass("alert-success")
            }

            parentAlert.text("Successfully updated display name!")
            parentAlert.removeClass("d-none")
            parentAlert.removeAttr("style")

            input.val("")

            modalText.text(data.newName)
            parentText.text(data.newName)
            $("#displayNameHeader").text(data.newName)
            $("#lastUpdated").text(unix2timestamp(moment().unix()))

            setTimeout(() => {
                parentAlert.fadeOut("slow")
            }, 2250)

            modal.modal("toggle")
        } else {
            if (error.hasClass("alert-success")) {
                error.removeClass("alert-success")
                error.addClass("alert-danger")
            }

            error.text("Unknown error occurred")
            error.removeClass("d-none")
        }
    })
}

function saveEditInternalNameChanges() {
    let spinner = $("#spinner-editInternalNameChanges")
    let modal = $("#editInternalNameModal")
    let modalText = $("#internalNameModalText")
    let error = $("#internalNameModalError")
    let parentText = $("#internalName")
    let parentAlert = $("#modification-update")

    if (!error.hasClass("d-none")) {
        error.addClass("d-none")
    }

    spinner.removeClass("d-none")

    let id = $('meta[name="rboxlo-application-id"]').attr("content")
    let csrf = $('meta[name="rboxlo-csrf"]').attr("content")
    let input = $("#editInternalNameInput")

    if ((input.val()).length == 0) {
        if (error.hasClass("alert-success")) {
            error.removeClass("alert-success")
            error.addClass("alert-danger")
        }

        error.text("The new internal name must not be empty.")
        error.removeClass("d-none")

        return
    }

    fetch(`${window.rboxlo.domain}/games/application/update-internal-name`, {
        method: "POST",
        body: new URLSearchParams({
            "id": id,
            "_csrf": csrf,
            "name": input.val()
        })
    })
    .then(res => res.json())
    .then(data => {
        spinner.addClass("d-none")

        if (data.success) {
            if (parentAlert.hasClass("alert-danger")) {
                parentAlert.removeClass("alert-danger")
                parentAlert.addClass("alert-success")
            }

            parentAlert.text("Successfully updated internal name!")
            parentAlert.removeClass("d-none")
            parentAlert.removeAttr("style")

            input.val("")

            modalText.text(data.newName)
            parentText.text(data.newName)
            $("#lastUpdated").text(unix2timestamp(moment().unix()))

            setTimeout(() => {
                parentAlert.fadeOut("slow")
            }, 2250)

            modal.modal("toggle")
        } else {
            if (error.hasClass("alert-success")) {
                error.removeClass("alert-success")
                error.addClass("alert-danger")
            }

            error.text("Unknown error occurred")
            error.removeClass("d-none")
        }
    })
}

// Delete Application
function deleteApplication() {
    let removeFromList = (window.rboxlo.hasOwnProperty("currentlyDeleting"))
    let csrf = $('meta[name="rboxlo-csrf"]').attr("content")

    let id
    if (removeFromList) {
        id = parseInt(window.rboxlo.currentlyDeleting)
    } else {
        id = $('meta[name="rboxlo-application-id"]').attr("content")
    }

    fetch(`${window.rboxlo.domain}/games/application/delete-api`, {
        method: "POST",
        body: new URLSearchParams({
            "id": id,
            "_csrf": csrf
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (removeFromList) {
                let alert = $("#modification-update")

                $(`[data-rboxlo-application-id="${id}"`).remove()
                alert.text(`Successfully deleted application "${data.name}"!`)
                alert.removeClass("d-none")
                alert.removeAttr("style")
                $("#deleteApplicationModal").modal("hide")
                refreshData()

                setTimeout(() => {
                    alert.fadeOut("slow")
                }, 2250)
            } else {
                window.location.href = `${window.rboxlo.domain}/games/application/modify`
            }
        }
    })
}

function fetchApplicationDataDelete(firstTime = false) {
    if (!firstTime) {
        $("#data").addClass("d-none")
        $("#data-loading").removeClass("d-none")
    }
    
    let body = $("#data-body")
    body.html("")

    if (!($("#data-empty").hasClass("d-none"))) {
        $("#data-empty").addClass("d-none")
    }

    fetch(`${window.rboxlo.domain}/games/application/json`)
        .then(res => res.json())
        .then(data => {
            if (data.length == 0) {
                $("#data-loading").addClass("d-none")
                $("#data-empty").removeClass("d-none")
            } else {
                $(data).each((_, app) => {
                    let container = $("<tr>", {
                        "data-rboxlo-application-id": app.id,
                        onclick: `deleteListClicked(${app.id})`,
                        style: "cursor: pointer"
                    })

                    let id = $("<td>", { class: "align-middle text-center" }).append(app.id)
                    let uuid = $("<td>", { class: "align-middle" }).append($("<code>").append(app.uuid))
                    let lastVersionUUID = $("<td>", { class: "align-middle" })

                    if (app.last_deployed_version_uuid.length > 0) {
                        lastVersionUUID.append($("<code>").append(app.last_deployed_version_uuid))
                    } else {
                        lastVersionUUID.append($("<i>", { class: "text-muted" }).append("none"))
                    }

                    let internalName = $("<td>", { class: "align-middle" }).append($("<code>").append(app.internal_name))
                    let displayName = $("<td>", { class: "align-middle"}).append(app.display_name)
                    let lastUpdatedTimestamp = $("<td>", { class: "align-middle" }).append(unix2timestamp(app.last_updated_timestamp))
                    let createdTimestamp = $("<td>", { class: "align-middle" }).append(unix2timestamp(app.created_timestamp))
                    
                    container.append(id)
                    container.append(uuid)
                    container.append(lastVersionUUID)
                    container.append(internalName)
                    container.append(displayName)
                    container.append(lastUpdatedTimestamp)
                    container.append(createdTimestamp)

                    body.append(container)
                })

                setTimeout(() => {
                    $("#data-loading").addClass("d-none")
                    $("#data").removeClass("d-none")
                }, 200)
            }
        })
}

function deleteListClicked(id) {
    window.rboxlo.currentlyDeleting = id
    $("#deleteApplicationModal").modal("show")
}