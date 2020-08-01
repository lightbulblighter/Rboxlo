function join(id) {
    Swal.fire({
        title: "Waiting for server...",
        allowOutsideClick: () => !Swal.isLoading(),
        onBeforeOpen: () => {
            Swal.showLoading()
            return fetch(`/api/rbx/game/client/matchmaker?id=${id}`)
                .then(response => {
                    if (!response.success) {
                        throw new Error(response.message)
                    }

                    return response.json()
                })
                .catch(error => {
                    swal.fire({
                        title: "An error occured.",
                        text: error,
                        type: "error"
                    })
                })
                .then((result) => {
                    if (result.payload) {
                        Swal.fire({
                            title: "Launching Rboxlo...",
                            text: "This shouldn't take long.",
                            type: "success"
                        })

                        window.location.href = result.payload
                    } else {
                        Swal.fire({
                            title: "An error occurred.",
                            text: result.message,
                            type: "error"
                        })
                    }
                })
            // .then((result)
        }
    })
}