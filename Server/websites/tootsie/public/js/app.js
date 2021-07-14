window.rboxlo = []

function timestamp(unix) {
    return moment.unix(unix).format("L @ h:mm A")
}

function copy(text) {
    console.log("[rboxlo->info]: app::copy")

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text)
    } else {
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
            console.log(`[rboxlo->error]: app::copy | failed to copy text "${text}" because "${err}"`)
        }

        area.remove()
    }
}

function url(path) {
    return `${window.rboxlo.domain}${path}`
}

$(document).ready(() => {
    console.log(`[rboxlo->info]: app::ready | document init`)

    if ($("#tabination").length) {
        $("#tabination a").click(function (e) {
            e.preventDefault()
            $(this).tab('show')
        })
    }
})