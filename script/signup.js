document.addEventListener('DOMContentLoaded', function() {
    const submit = document.getElementById('submit');

    submit.addEventListener('click', function(e) {
        e.preventDefault();

        $.ajax({
            url: '../controllers/controller.php',
            type: 'POST',
            data: {
                fname: document.getElementById('fname').value,
                lname: document.getElementById('lname').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                yearlvl: document.getElementById('yearlvl').value,
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