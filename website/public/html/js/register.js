function submitRegister()
{
    var information = {
        username: $("#username").val(),
        password: $("#password").val(),
        email: $("#email").val(),
        confirmed_password: $("#confirmed_password").val(),
        recaptcha: grecaptcha.getResponse(),
        csrf: $("meta[name='csrf-token']").attr("content")
    }

    endpoint("/authentication/register", "POST", information, (response) =>
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
                location.replace("/my/dashboard")
            }, 2000)
        }
        else
        {
            $(".register-checkbox").removeAttr("disabled", "disabled")
            $(".register-input").removeAttr("readonly, readonly")
            $(".register-input").removeAttr("disabled", "disabled")
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
    $("#register-form").on("submit", function(e) {
        $(".register-checkbox").attr("disabled", "disabled")
        $(".register-input").attr("readonly, readonly")
        $(".register-input").attr("disabled", "disabled")

        e.preventDefault()
        grecaptcha.execute()
        submitRegister()
    })
})