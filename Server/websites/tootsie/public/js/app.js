window.rboxlo = [];

// Document init
document.onreadystatechange = function() {
    if (document.readyState == "interactive") {
        // Active links
        let elements = document.querySelectorAll("[laid]");

        for (let i = 0; i < elements.length; i++) {
            let element = elements[i];

            if (element.getAttribute("laid") == window.rboxlo.laid) {
                let link = element.getElementsByTagName("a")[0];
                
                element.classList.add("active");
                link.classList.add("text-white");
            }
        }

        // Popovers
        $('[data-toggle="popover"]').popover({
            placement: "bottom"
        });

        $('[data-toggle="popover"]').click(() => {
            setTimeout(function () {
                $(".popover").fadeOut("slow");
            }, 1000);
        });
    }
}

// Copying
function copyTextToClipboard(text) {
    if (!navigator.clipboard || !window.isSecureContext) {
        let area = document.createElement("textarea");

        area.style.position = "fixed";
        area.style.top = 0;
        area.style.left = 0;

        area.style.width = "2em";
        area.style.height = "2em";

        area.style.padding = 0;

        area.style.border = "none";
        area.style.outline = "none";
        area.style.boxShadow = "none";

        area.style.background = "transparent";

        area.value = text;

        document.body.appendChild(area);
        area.focus();
        area.select();
        
        try {
            document.execCommand("copy");
        } catch (e) { }

        document.body.removeChild(area);
    } else {
        navigator.clipboard.writeText(text);
    }
}

function copyUUID() {
    copyTextToClipboard(document.getElementById("uuid").textContent);
}

function copyLastVersionUUID() {
    copyTextToClipboard(document.getElementById("lastVersionUUID").textContent);
}

function copyInternalName() {
    copyTextToClipboard(document.getElementById("internalName").textContent);
}