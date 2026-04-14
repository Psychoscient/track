document.addEventListener('DOMContentLoaded', function() {
    const submit = document.getElementById('submit');
    const fname = document.getElementById('fname');
    const lname = document.getElementById('lname');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const yearlvl = document.getElementById('yearlvl');

    const fields = [fname, lname];

    Utils.keydown(fields);

    submit.addEventListener('click', function(e) {
        e.preventDefault();

        $.ajax({
            url: '../controllers/controller.php',
            type: 'POST',
            data: {
                fname: fname.value,
                lname: lname.value,
                email: email.value,
                password: password.value,
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
                    });
                    
                    Utils.resetFields();
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