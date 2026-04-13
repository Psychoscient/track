document.addEventListener('DOMContentLoaded', function() {
    const logout = document.getElementById('logout');

    logout.addEventListener('click', function(e){
        e.preventDefault();

        console.log("Logout button clicked");
        $.ajax({
            url: '../controllers/controller.php',
            type: 'POST',
            data: {
                action: 'logout'
            },
            success: function(response) {
                console.log(response);
                let res = JSON.parse(response);

                if (!res.status) {
                    Swal.fire({
                        title: "Error!",
                        text: res.message,
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                } else {
                    Swal.fire({
                        title: "Success!",
                        text: "Logout successful.",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then((click) => {
                        if (click.isConfirmed) {
                            Utils.redirect(res.redirect);
                        }
                    })
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: "Error!",
                    text: "Something went wrong.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        });
    });

});