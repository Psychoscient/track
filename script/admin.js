document.addEventListener('DOMContentLoaded', function() {
    const create = document.getElementById('create');
    const buttons = document.querySelectorAll('.dashboard-btn');
    const applicationButtons = document.querySelectorAll('.application-action-btn');
    const logoutBtn = document.getElementById('logout');

    initializeManagedTable({
        tableId: 'userManagementTable',
        searchInputId: 'userTableSearch',
        summaryId: 'userTableSummary',
        paginationId: 'userTablePagination',
        itemLabel: 'user',
        emptyMessage: 'No matching users found.'
    });

    initializeManagedTable({
        tableId: 'organizerApplicationsTable',
        searchInputId: 'applicationTableSearch',
        summaryId: 'applicationTableSummary',
        paginationId: 'applicationTablePagination',
        itemLabel: 'application',
        emptyMessage: 'No matching organizer applications found.'
    });

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
                role: document.getElementById('edit_role').value,
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

    function initializeManagedTable(config) {
        const table = document.getElementById(config.tableId);
        const searchInput = document.getElementById(config.searchInputId);
        const summary = document.getElementById(config.summaryId);
        const pagination = document.getElementById(config.paginationId);

        if (!table || !searchInput || !summary || !pagination) {
            return;
        }

        const tbody = table.querySelector('[data-table-body]');
        const prevButton = pagination.querySelector('[data-pagination-prev]');
        const nextButton = pagination.querySelector('[data-pagination-next]');
        const pageLabel = pagination.querySelector('[data-pagination-pages]');
        const paginationStatus = pagination.querySelector('[data-pagination-status]');
        const pageSize = Number(table.dataset.pageSize) || 10;
        const dataRows = Array.from(tbody.querySelectorAll('[data-table-row]'));
        const originalEmptyRow = dataRows.length === 0 ? tbody.querySelector('tr') : null;
        const noResultsRow = createNoResultsRow(table, config.emptyMessage);
        let currentPage = 1;

        function getSearchValue() {
            return searchInput.value.trim().toLowerCase();
        }

        function getFilteredRows() {
            const query = getSearchValue();

            if (!query) {
                return dataRows;
            }

            return dataRows.filter((row) => row.textContent.toLowerCase().includes(query));
        }

        function render() {
            const filteredRows = getFilteredRows();
            const totalRows = filteredRows.length;
            const totalPages = Math.max(1, Math.ceil(totalRows / pageSize));
            currentPage = Math.min(currentPage, totalPages);

            dataRows.forEach((row) => row.classList.add('hidden'));
            noResultsRow.remove();

            if (originalEmptyRow) {
                originalEmptyRow.classList.remove('hidden');
            } else if (totalRows === 0) {
                tbody.appendChild(noResultsRow);
            } else {
                const startIndex = (currentPage - 1) * pageSize;
                const visibleRows = filteredRows.slice(startIndex, startIndex + pageSize);
                visibleRows.forEach((row) => row.classList.remove('hidden'));
            }

            const visibleStart = totalRows === 0 ? 0 : ((currentPage - 1) * pageSize) + 1;
            const visibleEnd = totalRows === 0 ? 0 : Math.min(currentPage * pageSize, totalRows);
            const itemLabel = totalRows === 1 ? config.itemLabel : `${config.itemLabel}s`;

            summary.textContent = totalRows === dataRows.length
                ? `${totalRows} ${itemLabel}`
                : `${totalRows} matching ${itemLabel}`;

            paginationStatus.textContent = totalRows === 0
                ? `Showing 0 of ${dataRows.length}`
                : `Showing ${visibleStart}-${visibleEnd} of ${totalRows}`;

            pageLabel.textContent = `Page ${currentPage} of ${totalPages}`;
            prevButton.disabled = currentPage === 1 || totalRows === 0;
            nextButton.disabled = currentPage === totalPages || totalRows === 0;
            pagination.classList.toggle('hidden', dataRows.length === 0);
        }

        searchInput.addEventListener('input', function() {
            currentPage = 1;
            render();
        });

        prevButton.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                render();
            }
        });

        nextButton.addEventListener('click', function() {
            const totalPages = Math.max(1, Math.ceil(getFilteredRows().length / pageSize));

            if (currentPage < totalPages) {
                currentPage++;
                render();
            }
        });

        render();
    }

    function createNoResultsRow(table, message) {
        const row = document.createElement('tr');
        const cell = document.createElement('td');
        const columnCount = table.querySelectorAll('thead th').length;

        cell.colSpan = columnCount;
        cell.className = 'p-6 text-center text-ust-gray';
        cell.innerHTML = `
            <i class="fas fa-search text-2xl mb-2 block opacity-50"></i>
            ${message}
        `;

        row.appendChild(cell);
        return row;
    }
});
