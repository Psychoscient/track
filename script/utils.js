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

