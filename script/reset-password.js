document.addEventListener('DOMContentLoaded', function() {
    const resetForm = document.getElementById('resetForm');
    const newPassword = document.getElementById('newPassword');
    const confirmPassword = document.getElementById('confirmPassword');

    // Function to check password match and update UI
    function checkPasswordMatch() {
        const pwd1 = newPassword.value;
        const pwd2 = confirmPassword.value;

        if (pwd1 === '' && pwd2 === '') {
            // Reset to default state
            newPassword.style.borderColor = '#e5e7eb';
            confirmPassword.style.borderColor = '#e5e7eb';
        } else if (pwd1 === pwd2 && pwd1 !== '') {
            // Passwords match - green
            newPassword.style.borderColor = '#22c55e';
            confirmPassword.style.borderColor = '#22c55e';
        } else if (pwd1 !== '' || pwd2 !== '') {
            // Passwords don't match - red
            newPassword.style.borderColor = '#ef4444';
            confirmPassword.style.borderColor = '#ef4444';
        }
    }

    // Add event listeners for real-time validation
    newPassword.addEventListener('input', checkPasswordMatch);
    confirmPassword.addEventListener('input', checkPasswordMatch);

    resetForm.addEventListener('submit', function(e){
        e.preventDefault();

        // Validate passwords match before submission
        if (newPassword.value !== confirmPassword.value) {
            Swal.fire({
                title: "Error!",
                text: "Passwords do not match!",
                icon: "error",
                confirmButtonText: "OK"
            });
            return;
        }

        if (newPassword.value === '') {
            Swal.fire({
                title: "Error!",
                text: "Please enter a password!",
                icon: "error",
                confirmButtonText: "OK"
            });
            return;
        }

        console.log($(resetForm).serialize());

        $.ajax({
            url: '../controllers/controller.php',
            type: 'POST',
            data: $(resetForm).serialize(),
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
                        text: res.message,
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then((click) => {
                        if (click.isConfirmed) {
                            window.location.href = '/Track/views/login.php';
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: "Error!",
                    text: "Error: " + xhr + " - " + status + " - " + error,
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        });
    });
});
