document.addEventListener('DOMContentLoaded', function() {
    const logoutBtn = document.getElementById('logout');

    logoutBtn.addEventListener('click', (e) => {
        e.preventDefault();

        Utils.logout();
    });
});