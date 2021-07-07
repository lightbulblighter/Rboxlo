window.rboxlo = []

$(document).ready(() => {
    if ($("#tabination").length) {
        $("#tabination a").click(function(e){
            e.preventDefault();
            $(this).tab('show');
        });
    }
})