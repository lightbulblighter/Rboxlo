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
})

// Copying
function copy(element) {
    let text = $(`#${element}`).text()

    if (!navigator.clipboard || !window.isSecureContext) {
        let area = $("<textarea>", {
            style: "position: fixed; top: 0; width: 2em; height: 2em; padding: 0; border: none; outline: none; boxShadow: none; background: transparent;"
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
function fetchApplicationDataModify() {
    $("#data").addClass("d-none")
    $("#data-loading").removeClass("d-none")

    let body = $("#data-body")
    body.html("")

    fetch(`${window.rboxlo.domain}/games/application/json`)
        .then(res => res.json())
        .then(data => {
            if (data.length == 0) {
                $("#data-loading").addClass("d-none")
                $("#data-empty").removeClass("d-none")
            } else {
                $(data).each((i, app) => {
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
                        type: "button"
                    }).append($("<i>", {
                        class: "fas fa-trash"
                    })))

                    let deploy = $("<td>", {
                        class: "align-middle text-center table-button",
                        scope: "row"
                    }).append($("<button>", {
                        class: "btn btn-success btn-sm text-white",
                        type: "button"
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
                        lastVersionUUID.append($("<i>").append("none"))
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