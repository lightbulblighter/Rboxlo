window.rboxlo = []

function loginSubmit(token) {
    document.getElementById("login-form").submit()
}

// Active link in navbar
$(document).ready(() => {
    let path = (window.location.href).split("?")[0]
    let elements = $(".nav-link")

    elements.each((i, element) => {
        if (element.attr("href") == path) {
            element.attr("href", "#")
            element.attr("aria-current", "page")

            element.addClass("active")
        }
    })
})