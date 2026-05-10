document.addEventListener('DOMContentLoaded', function() {
    const create = document.getElementById('create');
    const buttons = document.querySelectorAll('.dashboard-btn');
    const applicationButtons = document.querySelectorAll('.application-action-btn');
    const logoutBtn = document.getElementById('logout');

    logoutBtn.addEventListener('click', (e) => {
        e.preventDefault();

        Utils.logout();
    });
    
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

    applicationButtons.forEach((btn) => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            const action = btn.dataset.action;
            const applicationID = btn.dataset.applicationid;

            if (action === 'approve') {
                reviewApplication(applicationID, 'organizer-approve', 'approve');
            } else if (action === 'reject') {
                reviewApplication(applicationID, 'organizer-reject', 'reject');
            }
        });
    });

    // Handle update submit button
    const updateBtn = document.getElementById('updateSubmitBtn');
    if (updateBtn) {
        updateBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const userID = document.getElementById('editModal').dataset.userID;
            submitUpdate(userID);
        });
    }

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

                console.log(res);

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
                    text: xhr.responseText,
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

    function reviewApplication(applicationID, controllerAction, verb) {
        Swal.fire({
            title: `${verb.charAt(0).toUpperCase() + verb.slice(1)} application?`,
            text: `This will ${verb} the organizer application.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: verb.charAt(0).toUpperCase() + verb.slice(1),
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: '../controllers/controller.php',
                type: 'POST',
                data: {
                    applicationID: applicationID,
                    action: controllerAction
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
                error: function(xhr) {
                    Swal.fire({
                        title: "Error!",
                        text: xhr.responseText || "Something went wrong.",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
        });
    }
});
