document.addEventListener('DOMContentLoaded', function() {
    const email = document.getElementById('email');
    const submit = document.getElementById('submit');

    submit.addEventListener('click', function(e){
        e.preventDefault();

        if (!email.value || email.value == '') {
            Swal.fire({
                title: "Error!",
                text: "No empty fields.",
                icon: "error",
                confirmButtonText: "OK"
            });

            return;
        }

        if (!Utils.isValidEmail(email.value)) {
            Swal.fire({
                title: "Error!",
                text: "Invalid email format.",
                icon: "error",
                confirmButtonText: "OK"
            }).then((click) => {
                if(click.isConfirmed) {
                    email.value = "";
                }
            });

            return;
        }

        $.ajax({
            url: '../controllers/controller.php',
            type: 'POST',
            data: {
                email: email.value,
                action: 'forgot-password'
            },
            success: function(response) {
                console.log(response);
                let res = JSON.parse(response);

                if (!res.status) {
                    Swal.fire({
                        title: "Success!",
                        text: "Email sent.",
                        icon: "success",
                        confirmButtonText: "OK"
                    });
                } else {
                    Swal.fire({
                        title: "Success!",
                        text: res.message,
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then((click) => {
                        if (click.isConfirmed) {
                            email.value = "";
                        }
                    })
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: "Error!",
                    text: "Error: " + xhr,
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        });
    });
});