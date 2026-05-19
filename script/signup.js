document.addEventListener('DOMContentLoaded', function() {
    const submit = document.getElementById('submit');
    const fname = document.getElementById('fname');
    const lname = document.getElementById('lname');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');
    const yearlvl = document.getElementById('yearlvl');

    const fields = [fname, lname];

    Utils.keydown(fields);

    function checkPasswordMatch() {
        const pwd1 = password.value;
        const pwd2 = confirmPassword.value;

        if (pwd1 === '' && pwd2 === '') {
            password.style.borderColor = '#e5e7eb';
            confirmPassword.style.borderColor = '#e5e7eb';
        } else if (pwd1 === pwd2 && pwd1 !== '') {
            password.style.borderColor = '#22c55e';
            confirmPassword.style.borderColor = '#22c55e';
        } else if (pwd1 !== '' || pwd2 !== '') {
            password.style.borderColor = '#ef4444';
            confirmPassword.style.borderColor = '#ef4444';
        }
    }

    password.addEventListener('input', checkPasswordMatch);
    confirmPassword.addEventListener('input', checkPasswordMatch);

    submit.addEventListener('click', function(e) {
        e.preventDefault();

        const validate = Utils.validateSignup(fname, lname, email, password, confirmPassword);
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
                fname: fname.value,
                lname: lname.value,
                email: email.value,
                password: password.value,
                confirmPassword: confirmPassword.value,
                yearlvl: yearlvl.value,
                action: 'signup'
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
                        text: "User created successfully.",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then((click) => {
                        if (click.isConfirmed) {
                            window.location.href = 'login.php';
                        }
                    });
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
