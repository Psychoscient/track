const Utils = {
    resetFields: function() {
        try {
            document.querySelectorAll('.input-field').forEach(field => {
                if (field.tagName === "SELECT") {
                    field.selectedIndex = 0;
                } else {
                    field.value = "";
                }
            });
        } catch(er) {
            console.error("Error: " + er.message);
        }
    },
    
    redirect: function(roleID) {
        try {
            if (roleID === 1) {
                window.location.href = "../views/dashboard.php";
            } else if (roleID === 2 || roleID === 3) {
                window.location.href = "../views/home.php";
            } else if (roleID === 0) {
                window.location.href = "../views/login.php";
            }

        } catch(er) {
            console.error("Error: " + er.message);
        }
    },

    logout: function() {
        try {
            $.ajax({
                url: '../controllers/controller.php',
                type: 'POST',
                data: {
                    action: 'logout'
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
                            text: "Logout successful.",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then((click) => {
                            if (click.isConfirmed) {
                                Utils.redirect(res.redirect);
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
                    })
                }
            })
        } catch(er) {
            return {
                status: false,
                message: "Error: " + er.message
            }
        }
    },

    validateSignup: function(fname, lname, email, pass) {
        try {
            if ((fname === '' || !fname.value) || (lname === '' || !lname.value) || (email === '' || !email.value) || (pass === '' || !pass.value)) {
                return {
                    status: false,
                    message: "Fill out all fields."
                };
            }
            
            if (parseInt(fname.value.length) > 20 || parseInt(lname.value.length) > 20) {
                return {
                    status: false,
                    message: "No more than 20 characters."
                }
            } 

            if (parseInt(pass.value.length) < 8) {
                return {
                    status: false,
                    message: "Password must be at least 8 characters."
                }
            }

            if (!Utils.isValidEmail(email.value)) {
                return {
                    status: false,
                    message: "Invalid email format."
                }
            }

            return {
                status: true,
                message: "Validation successful."
            };

        } catch(er) {
            return {
                status: false,
                message: "Error: " + er.message
            }
        }
    },

    isValidEmail: function(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    },

    validateLogin: function(fields) {
        try {
            for(let field of fields) {
                if (!field.value || field.value === '') {
                    return {
                        status: false,
                        message: "Fill out all fields."
                    };
                }

                if (parseInt(field.value) > 20) {
                    return {
                        status: false,
                        message: "No more than 20 characters."
                    }
                }

                return {
                    status: true
                };
            }
        } catch(er) {
            console.error("Error: " + er.message);
        }
    },
    keydown: function(fields) {
        const MAX_CHARS = 20;

        for (let field of fields) {
            field.addEventListener('keydown', (event) => {
                const specialKeys = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'];
                
                if (specialKeys.includes(event.key)) {
                    return;
                }

                if (field.value.length >= MAX_CHARS) {
                    event.preventDefault();
                    console.log("Maximum limit reached");
                }
            });
        }
        
    }
}   

