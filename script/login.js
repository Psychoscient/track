document.addEventListener('DOMContentLoaded', function() {
    const submit = document.getElementById('submit');
    const fields = document.querySelectorAll('.input-field');

    submit.addEventListener('click', function(e) {
        e.preventDefault();

        const validate = Utils.validateLogin(fields);
        console.log(validate);

        if(!validate.status) {
            Swal.fire({
                title: "Error!",
                text: validate.message,
                icon: "error",
                confirmButtonText: "OK"
            });
            return;
        }

        $.ajax({
            url: '../controllers/controller.php',
            type: 'POST',
            data: {
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                action: 'login'
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
                        text: "Login successful.",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then((click) => {
                        if (click.isConfirmed) {
                            Utils.resetFields();
                            Utils.redirect(res.role);
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
                
                Utils.resetFields();
            }
        });
    });
});