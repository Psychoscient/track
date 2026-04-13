document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.dashboard-btn');

    buttons.forEach((btn, index) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            
            const action = btn.dataset.action;
            const userID = btn.dataset.userid;

            if (action === 'update') {
                updateUser(userID);
            }

            else if (action === 'delete') {
                deleteUser(userID);
            }
        });
    });

    function updateUser(userID) {
        $.ajax({
            url: '../controllers/controller.php',
            type: 'POST',
            data: {
                fname: document.getElementById('fname').value,
                lname: document.getElementById('lname').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                yearlvl: document.getElementById('yearlvl').value,
                userID: `${userID}`,
                action: 'update'
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
                        text: "Update successful.",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then((click) => {
                        if (click.isConfirmed) {
                            location.reload(true);
                            Utils.resetFields();
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
    }

    function deleteUser(userID) {
        $.ajax({
            url: '../controllers/controller.php',
            type: 'POST',
            data: {
                userID: userID,
                action: "delete"
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
                        text: "Delete successful.",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then((click) => {
                        if (click.isConfirmed) {
                            Utils.resetFields();
                            location.reload(true);
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
    }
});