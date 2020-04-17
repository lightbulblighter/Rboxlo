function endpoint(endpoint, method, form, callback)
{
    $.ajax({
        url: "/endpoints" + endpoint,
        type: method,

        contentType: "application/json; charset=utf-8",
        dataType: "json",
        data: JSON.stringify({information: form}),

        success: callback
    })
}