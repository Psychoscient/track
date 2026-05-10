document.addEventListener('DOMContentLoaded', function() {
    const logoutBtn = document.getElementById('logout');
    const applyOrganizerBtn = document.getElementById('applyOrganizerBtn');

    logoutBtn.addEventListener('click', (e) => {
        e.preventDefault();

        Utils.logout();
    });

    if (applyOrganizerBtn) {
        applyOrganizerBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const reasonField = document.getElementById('organizerReason');
            const reason = reasonField ? reasonField.value.trim() : '';

            if (!reason) {
                Swal.fire({
                    title: "Error!",
                    text: "Please provide your reason for applying.",
                    icon: "error",
                    confirmButtonText: "OK"
                });

                return;
            }

            $.ajax({
                url: '../controllers/controller.php',
                type: 'POST',
                data: {
                    reason: reason,
                    action: 'organizer-apply'
                },
                success: function(response) {
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
                                location.reload(true);
                            }
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: "Error!",
                        text: "Something went wrong.",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
        });
    }
});
