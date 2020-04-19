function endpoint(endpoint, method, form, callback)
{
    $.ajax({
        url: "/endpoints" + endpoint,
        type: method,

        dataType: "json",
        data: {
            information: JSON.stringify(form)
        },

        success: callback
    })
}