function submitLogin()
{
    var information = {
        username: $("#username").val(),
        password: $("#password").val(),
        csrf: $("meta[name='csrf-token']").attr("content")
    }

    endpoint("/authentication/login", "POST", information, (response) =>
    {
        toastr.options = {
            "closeButton": !response.success,
            "timeOut": response.success ? 2000 : 5000
        }

        toastr[response.success ? "success" : "error"](response.message, response.success ? "Success!" : "An error occurred.")

        if (response.success)
        {
            setTimeout(function()
            {
                window.history.back()
            }, 2000)
        }
        else
        {
            $(".login-input").removeAttr("readonly, readonly")
            $(".login-input").removeAttr("disabled", "disabled")
        }
    })
}

$('form input:not([type="submit"])').keypress(function (e)
{
    if (e.keyCode == 13)
    {
        e.preventDefault()
        return false
    }
})

$(function()
{
    $("#login-form").on("submit", function(e) {
        $(".login-input").attr("readonly, readonly")
        $(".login-input").attr("disabled", "disabled")

        e.preventDefault()
        submitLogin()
    })
})