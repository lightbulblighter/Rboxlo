function hookUpDataListEvents() {
    console.log("[rboxlo->info]: modify::hookUpDataListEvents")

    $(".delete-application").each(function () {
        $(this).on("click", () => {
            deleteApplicationModal($(this).parent().parent().attr("data-rboxlo-application-id"))
        })
    })

    $(".data-uuid-copy").each(function () {
        $(this).on("click", () => {
            copy($(this).parent().find(".data-uuid").text())
        })
    })

    $(".data-lastDeployedVersionUUID-copy").each(function () {
        if (!$(this).attr("disabled")) {
            $(this).on("click", () => {
                copy($(this).parent().find(".data-uuid").text())
            })
        }
    })

    $(".data-internalName-copy").each(function () { 
        $(this).on("click", () => {
            copy($(this).parent().find(".data-internalName").text())
        })
    })
}

function populate(initializing = false) {
    console.log(`[rboxlo->info]: modify::data | fetching data (initializing: ${initializing ? "TRUE" : "FALSE"})`)

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
                let container = $("<tr>", { "data-rboxlo-application-id": this.id })
                
                let edit = $("<td>", {
                    class: "align-middle text-center table-button",
                    scope: "row"
                }).append($("<a>", {
                    href: url(`/games/application/modify?id=${this.id}`)
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
                    class: "btn btn-danger btn-sm delete-application",
                    type: "button"
                }).append($("<i>", {
                    class: "fas fa-trash"
                })))

                let deploy = $("<td>", {
                    class: "align-middle text-center table-button",
                    scope: "row"
                }).append($("<a>", {
                    class: "btn btn-success btn-sm text-white",
                    type: "button",
                    href: url(`/games/application/deployment?id=${this.id}`)
                }).append($("<i>", {
                    class: "fas fa-upload"
                })))

                let id = $("<td>", {
                    class: "align-middle text-center"
                }).append(this.id)

                let displayName = $("<td>", { class: "align-middle" }).append(this.displayName)

                let internalName = $("<td>", {
                    class: "align-middle"
                }).append($("<div>", {
                    class: "d-flex justify-content-between align-items-center"
                }).append([
                    $("<code>", { class: "data-internalName" }).append(this.internalName),
                    $("<button>", { class: "btn btn-secondary btn-sm data-internalName-copy" }).append($("<i>", { class: "fas fa-clipboard" }))
                ]))

                let uuid = $("<td>", {
                    class: "align-middle"
                }).append($("<div>", {
                    class: "d-flex justify-content-between align-items-center"
                }).append([
                    $("<code>", { class: "data-uuid" }).append(this.uuid),
                    $("<button>", { class: "btn btn-secondary btn-sm data-uuid-copy" }).append($("<i>", { class: "fas fa-clipboard" }))
                ]))

                let lastDeployedVersionUUID = $("<td>", {
                    class: "align-middle"
                })
                
                if (this.lastDeployedVersionUUID.length > 0) {
                    lastDeployedVersionUUID.append($("<div>", {
                        class: "d-flex justify-content-between align-items-center"
                    }).append([
                        $("<code>", { class: "data-lastDeployedVersionUUID" }).append(this.lastDeployedVersionUUID),
                        $("<button>", { class: "btn btn-secondary btn-sm data-lastDeployedVersionUUID-copy" }).append($("<i>", { class: "fas fa-clipboard" }))
                    ]))
                } else {
                    lastDeployedVersionUUID.append($("<div>", {
                        class: "d-flex justify-content-between align-items-center"
                    }).append([
                        $("<i>", { class: "text-muted" }).append("none"),
                        $("<button>", { class: "btn btn-secondary btn-sm", disabled: "" }).append($("<i>", { class: "fas fa-clipboard" }))
                    ]))
                }

                let lastUpdatedTimestamp = $("<td>", { class: "align-middle" }).append(timestamp(this.lastUpdatedTimestamp))
                let createdTimestamp = $("<td>", { class: "align-middle" }).append(timestamp(this.createdTimestamp))

                container.append(edit)
                container.append(trash)
                container.append(deploy)
                container.append(id)
                container.append(displayName)
                container.append(internalName)
                container.append(uuid)
                container.append(lastDeployedVersionUUID)
                container.append(lastUpdatedTimestamp)
                container.append(createdTimestamp)

                body.append(container)
            })

            hookUpDataListEvents()

            setTimeout(() => {
                $("#data-loading").addClass("d-none")
                $("#data").removeClass("d-none")
            }, 200)
        }
    })
}

function regenerateUUID() {
    let spinner = $("#regenerate-uuid-spinner")
    spinner.removeClass("d-none")

    let id = $('meta[name="rboxlo-application-id"]').attr("content")
    let csrf = $('meta[name="rboxlo-csrf"]').attr("content")

    fetch(url("/api/games/application/uuid"), {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            _csrf: csrf,
            id: id,
            method: "regenerate"
        })
    })
    .then(res => res.json())
    .then(data => {
        spinner.addClass("d-none")
        let alert = $("#update")

        if (data.success) {
            if (alert.hasClass("alert-danger")) {
                alert.removeClass("alert-danger")
                alert.addClass("alert-success")
            }

            alert.text("Successfully regenerated application UUID!")
            alert.removeClass("d-none")
            alert.removeAttr("style")

            $("#uuid").text(data.new)
            $("#last-updated").text(timestamp(moment().unix()))
        } else {
            if (alert.hasClass("alert-success")) {
                alert.removeClass("alert-success")
                alert.addClass("alert-danger")
            }
            
            alert.text("Unknown error occurred")
            alert.removeClass("d-none")
            alert.removeAttr("style")
        }

        setTimeout(() => {
            update.fadeOut("slow")
        }, 2250)
    })
}

function displayNameSubmit() {
    console.log("[rboxlo->info]: modify::displayNameSubmit")

    let spinner = $("#display-name-spinner")
    let modalAlert = $("#display-name-update")
    
    spinner.removeClass("d-none")

    if (!modalAlert.hasClass("d-none")) {
        modalAlert.addClass("d-none")
    }

    let id = $('meta[name="rboxlo-application-id"]').attr("content")
    let csrf = $('meta[name="rboxlo-csrf"]').attr("content")
    let input = $("#display-name-input")
    let modal = $("#display-name-modal")
    let formAlert = $("#update")
    let value = input.val()

    if (value.length == 0) {
        if (modalAlert.hasClass("alert-success")) {
            modalAlert.removeClass("alert-success")
            modalAlert.addClass("alert-danger")
        }

        modalAlert.text("The new display name must not be empty.")
        modalAlert.removeClass("d-none")

        return
    }

    fetch(url("/api/games/application/set"), {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            _csrf: csrf,
            id: id,
            column: "display_name",
            value: value
        })
    })
    .then(res => res.json())
    .then(data => {
        spinner.addClass("d-none")

        if (data.success) {
            if (formAlert.hasClass("alert-danger")) {
                formAlert.removeClass("alert-danger")
                formAlert.addClass("alert-success")
            }

            formAlert.text("Successfully updated display name!")
            formAlert.removeClass("d-none")
            formAlert.removeAttr("style")

            input.val("")

            for (let i = 0; i < $(".display-name").length; i++) {
                $(".display-name").eq(i).text(value)
            }

            $("#last-updated").text(timestamp(moment().unix()))

            setTimeout(() => {
                formAlert.fadeOut("slow")
            }, 2250)

            modal.modal("toggle")
        } else {
            if (modalAlert.hasClass("alert-success")) {
                modalAlert.removeClass("alert-success")
                modalAlert.addClass("alert-danger")
            }

            modalAlert.text("Unknown error occurred")
            modalAlert.removeClass("d-none")
        }
    })
}

function internalNameSubmit() {
    console.log("[rboxlo->info]: modify::internalNameSubmit")

    let spinner = $("#internal-name-spinner")
    let modalAlert = $("#internal-name-update")
    
    spinner.removeClass("d-none")

    if (!modalAlert.hasClass("d-none")) {
        modalAlert.addClass("d-none")
    }

    let id = $('meta[name="rboxlo-application-id"]').attr("content")
    let csrf = $('meta[name="rboxlo-csrf"]').attr("content")
    let input = $("#internal-name-input")
    let modal = $("#internal-name-modal")
    let formAlert = $("#update")
    let value = input.val()

    if (value.length == 0) {
        if (modalAlert.hasClass("alert-success")) {
            modalAlert.removeClass("alert-success")
            modalAlert.addClass("alert-danger")
        }

        modalAlert.text("The new internal name must not be empty.")
        modalAlert.removeClass("d-none")

        return
    }

    fetch(url("/api/games/application/set"), {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            _csrf: csrf,
            id: id,
            column: "internal_name",
            value: value
        })
    })
    .then(res => res.json())
    .then(data => {
        spinner.addClass("d-none")

        if (data.success) {
            if (formAlert.hasClass("alert-danger")) {
                formAlert.removeClass("alert-danger")
                formAlert.addClass("alert-success")
            }

            formAlert.text("Successfully updated internal name!")
            formAlert.removeClass("d-none")
            formAlert.removeAttr("style")

            input.val("")

            for (let i = 0; i < $(".internal-name").length; i++) {
                $(".internal-name").eq(i).text(value)
            }

            $("#last-updated").text(timestamp(moment().unix()))

            setTimeout(() => {
                formAlert.fadeOut("slow")
            }, 2250)

            modal.modal("toggle")
        } else {
            if (modalAlert.hasClass("alert-success")) {
                modalAlert.removeClass("alert-success")
                modalAlert.addClass("alert-danger")
            }

            modalAlert.text("Unknown error occurred")
            modalAlert.removeClass("d-none")
        }
    })
}

function deleteApplicationModal(id) {
    $("#delete-application-submit").off("click").on("click", () => { deleteApplication(id) })
    $("#delete-application-modal").modal("show")
}

function deleteApplication(id, redirect = false) {
    console.log(`[rboxlo->info]: modify::deleteApplication | deleting application with ID ${id}`)

    let csrf = $('meta[name="rboxlo-csrf"]').attr("content")
    let spinner = $("#delete-spinner")
    
    spinner.removeClass("d-none")

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
            if (redirect) {
                window.location.href = url("/games/application/modify")
                return
            }

            alert.text(`Successfully deleted application "${data.deleted.displayName}"!`)
            alert.removeClass("d-none")
            alert.removeAttr("style")

            spinner.addClass("d-none")
            
            $("#delete-application-modal").modal("hide")

            populate()

            setTimeout(() => {
                alert.fadeOut("slow")
            }, 2250)
        }
    })
}

$(document).ready(() => {
    if ($("#data").length) {
        // We are on the choosing an application page (since the data list exists)
        $("#data-refresh").on("click", () => { populate() })
        populate(true)
    } else {
        // We are on the application modification page (already chose one, is modifying it now)
        $("#regenerate-uuid").click(() => { regenerateUUID() })
        $("#display-name-submit").click(() => { displayNameSubmit() })
        $("#internal-name-submit").click(() => { internalNameSubmit() })
        $("#delete-application-submit").click(() => { deleteApplication($('meta[name="rboxlo-application-id"]').attr("content"), true) })
    }
})