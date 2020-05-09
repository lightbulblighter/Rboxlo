toastr.options = {
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": 300,
    "hideDuration": 1000,
    "extendedTimeOut": 1000,
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}

$(function () {
	$('[data-toggle="tooltip"]').tooltip()
})

function endpoint(endpoint, method, form, callback)
{
    $.ajax({
        url: "/api" + endpoint,
        type: method,

        dataType: "json",
        data: {
            information: JSON.stringify(form)
        },

        success: callback
    })
}