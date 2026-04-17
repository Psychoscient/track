document.addEventListener('DOMContentLoaded', function() {
    const create = document.getElementById('create');
    const buttons = document.querySelectorAll('.dashboard-btn');
    
    create.addEventListener('click', (e) => {
        e.preventDefault();

        console.log('create');

        $.ajax({
            url: '../controllers/controller.php',
            type: 'POST',
            data: {
                fname: document.getElementById('fname').value,
                lname: document.getElementById('lname').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                yearlvl: document.getElementById('yearlvl').value,
                role: document.getElementById('role').value,
                action: 'create'
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
                        text: "User created successfully.",
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
    });

    buttons.forEach((btn, index) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            
            const action = btn.dataset.action;
            const userID = btn.dataset.userid;

            if (action === 'edit') openEditModal(userID);
            else if (action === 'delete') deleteUser(userID);
        });
    });

    // Handle update submit button
    document.getElementById('updateSubmitBtn').addEventListener('click', function(e) {
        e.preventDefault();
        const userID = document.getElementById('editModal').dataset.userID;
        submitUpdate(userID);
    });

    function openEditModal(userID) {
        const row = document.querySelector(`button[data-userid="${userID}"]`).closest('tr');
        const cells = row.querySelectorAll('td');
        
        document.getElementById('edit_fname').value = cells[1].textContent.trim();
        document.getElementById('edit_lname').value = cells[2].textContent.trim();
        document.getElementById('edit_email').value = cells[3].textContent.trim();
        
        const yearLevelSelect = document.getElementById('edit_yearlvl');
        const roleSelect = document.getElementById('edit_role');
        
        yearLevelSelect.value = cells[4].dataset.yearLevelId || '';
        roleSelect.value = cells[5].dataset.roleId || '';
        document.getElementById('edit_password').value = '';
        
        // Open the modal
        window.openEditModal(userID);
    }

    function submitUpdate(userID) {
        $.ajax({
            url: '../controllers/controller.php',
            type: 'POST',
            data: {
                fname: document.getElementById('edit_fname').value,
                lname: document.getElementById('edit_lname').value,
                email: document.getElementById('edit_email').value,
                password: document.getElementById('edit_password').value,
                yearlvl: document.getElementById('edit_yearlvl').value,
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
                            window.closeEditModal();
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