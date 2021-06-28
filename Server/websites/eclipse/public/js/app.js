window.rboxlo = [];

function loginSubmit(token) {
    document.getElementById("login-form").submit();
}

// Active link in navbar
document.onreadystatechange = function() {
    if (document.readyState == "interactive") {
        let path = (window.location.href).split("?")[0];
        let elements = document.getElementsByClassName("nav-link");

        for (let i = 0; i < elements.length; i++) {
            if (elements[i].getAttribute("href") == path) {
                elements[i].classList.add("active");
                elements[i].setAttribute("href", "#");
                elements[i].setAttribute("aria-current", "page");
            }
        }
    }
}