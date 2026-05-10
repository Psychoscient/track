document.addEventListener('DOMContentLoaded', function() {
    const logoutBtn = document.getElementById('logout');
    const createEventBtn = document.getElementById('createEventBtn');
    const clearEventFormBtn = document.getElementById('clearEventForm');
    const editEventModal = document.getElementById('editEventModal');
    const updateEventBtn = document.getElementById('updateEventBtn');
    const closeEditEventModal = document.getElementById('closeEditEventModal');
    const cancelEditEventBtn = document.getElementById('cancelEditEventBtn');
    const actionButtons = document.querySelectorAll('.event-action-btn');

    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            Utils.logout();
        });
    }

    if (clearEventFormBtn) {
        clearEventFormBtn.addEventListener('click', function() {
            resetCreateEventForm();
        });
    }

    if (createEventBtn) {
        createEventBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const eventData = getCreateEventData();
            const validation = validateEventData(eventData);

            if (!validation.status) {
                showError(validation.message);
                return;
            }

            $.ajax({
                url: '../controllers/controller.php',
                type: 'POST',
                data: {
                    title: eventData.title,
                    description: eventData.description,
                    categoryID: eventData.categoryID,
                    location: eventData.location,
                    capacity: eventData.capacity,
                    startDateTime: eventData.startDateTime,
                    endDateTime: eventData.endDateTime,
                    statusID: eventData.statusID,
                    action: 'event-create'
                },
                success: function(response) {
                    let res = JSON.parse(response);

                    if (!res.status) {
                        showError(res.message);
                    } else {
                        Swal.fire({
                            title: 'Success!',
                            text: res.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((click) => {
                            if (click.isConfirmed) {
                                resetCreateEventForm();
                                location.reload(true);
                            }
                        });
                    }
                },
                error: function(xhr) {
                    showError(xhr.responseText || 'Something went wrong.');
                }
            });
        });
    }

    actionButtons.forEach((button) => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const action = button.dataset.action;
            const eventID = button.dataset.eventid;

            if (action === 'edit') {
                openEditEventModal(button.dataset);
            } else if (action === 'delete') {
                deleteEvent(eventID);
            }
        });
    });

    if (updateEventBtn) {
        updateEventBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const eventData = getEditEventData();
            const validation = validateEventData(eventData);

            if (!validation.status) {
                showError(validation.message);
                return;
            }

            $.ajax({
                url: '../controllers/controller.php',
                type: 'POST',
                data: {
                    eventID: eventData.eventID,
                    title: eventData.title,
                    description: eventData.description,
                    categoryID: eventData.categoryID,
                    location: eventData.location,
                    capacity: eventData.capacity,
                    startDateTime: eventData.startDateTime,
                    endDateTime: eventData.endDateTime,
                    statusID: eventData.statusID,
                    action: 'event-update'
                },
                success: function(response) {
                    let res = JSON.parse(response);

                    if (!res.status) {
                        showError(res.message);
                    } else {
                        Swal.fire({
                            title: 'Success!',
                            text: res.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((click) => {
                            if (click.isConfirmed) {
                                closeEventModal();
                                location.reload(true);
                            }
                        });
                    }
                },
                error: function(xhr) {
                    showError(xhr.responseText || 'Something went wrong.');
                }
            });
        });
    }

    if (closeEditEventModal) {
        closeEditEventModal.addEventListener('click', closeEventModal);
    }

    if (cancelEditEventBtn) {
        cancelEditEventBtn.addEventListener('click', closeEventModal);
    }

    if (editEventModal) {
        editEventModal.addEventListener('click', function(e) {
            if (e.target === editEventModal) {
                closeEventModal();
            }
        });
    }

    function resetCreateEventForm() {
        document.querySelectorAll('.create-event-field').forEach((field) => {
            if (field.tagName === 'SELECT') {
                field.selectedIndex = 0;
            } else {
                field.value = '';
            }
        });

        const statusField = document.getElementById('statusID');
        if (statusField) {
            const draftOption = Array.from(statusField.options).find((option) => option.text.toLowerCase() === 'draft');
            statusField.value = draftOption ? draftOption.value : '';
        }
    }

    function openEditEventModal(eventData) {
        if (!editEventModal) {
            return;
        }

        document.getElementById('edit_eventID').value = eventData.eventid || '';
        document.getElementById('edit_title').value = eventData.title || '';
        document.getElementById('edit_description').value = eventData.description || '';
        document.getElementById('edit_categoryID').value = eventData.categoryid || '';
        document.getElementById('edit_location').value = eventData.location || '';
        document.getElementById('edit_capacity').value = eventData.capacity || '';
        document.getElementById('edit_startDateTime').value = formatDateTimeForInput(eventData.startdatetime || '');
        document.getElementById('edit_endDateTime').value = formatDateTimeForInput(eventData.enddatetime || '');
        document.getElementById('edit_statusID').value = eventData.statusid || '';

        editEventModal.classList.remove('hidden');
    }

    function closeEventModal() {
        if (!editEventModal) {
            return;
        }

        editEventModal.classList.add('hidden');
    }

    function deleteEvent(eventID) {
        Swal.fire({
            title: 'Delete event?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc2626'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            $.ajax({
                url: '../controllers/controller.php',
                type: 'POST',
                data: {
                    eventID: eventID,
                    action: 'event-delete'
                },
                success: function(response) {
                    let res = JSON.parse(response);

                    if (!res.status) {
                        showError(res.message);
                    } else {
                        Swal.fire({
                            title: 'Success!',
                            text: res.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((click) => {
                            if (click.isConfirmed) {
                                location.reload(true);
                            }
                        });
                    }
                },
                error: function(xhr) {
                    showError(xhr.responseText || 'Something went wrong.');
                }
            });
        });
    }

    function getCreateEventData() {
        return {
            title: document.getElementById('title') ? document.getElementById('title').value.trim() : '',
            description: document.getElementById('description') ? document.getElementById('description').value.trim() : '',
            categoryID: document.getElementById('categoryID') ? document.getElementById('categoryID').value : '',
            location: document.getElementById('location') ? document.getElementById('location').value.trim() : '',
            capacity: document.getElementById('capacity') ? document.getElementById('capacity').value.trim() : '',
            startDateTime: document.getElementById('startDateTime') ? document.getElementById('startDateTime').value : '',
            endDateTime: document.getElementById('endDateTime') ? document.getElementById('endDateTime').value : '',
            statusID: document.getElementById('statusID') ? document.getElementById('statusID').value : ''
        };
    }

    function getEditEventData() {
        return {
            eventID: document.getElementById('edit_eventID') ? document.getElementById('edit_eventID').value : '',
            title: document.getElementById('edit_title') ? document.getElementById('edit_title').value.trim() : '',
            description: document.getElementById('edit_description') ? document.getElementById('edit_description').value.trim() : '',
            categoryID: document.getElementById('edit_categoryID') ? document.getElementById('edit_categoryID').value : '',
            location: document.getElementById('edit_location') ? document.getElementById('edit_location').value.trim() : '',
            capacity: document.getElementById('edit_capacity') ? document.getElementById('edit_capacity').value.trim() : '',
            startDateTime: document.getElementById('edit_startDateTime') ? document.getElementById('edit_startDateTime').value : '',
            endDateTime: document.getElementById('edit_endDateTime') ? document.getElementById('edit_endDateTime').value : '',
            statusID: document.getElementById('edit_statusID') ? document.getElementById('edit_statusID').value : ''
        };
    }

    function validateEventData(eventData) {
        if (!eventData.title || !eventData.description || !eventData.categoryID || !eventData.location || !eventData.startDateTime || !eventData.endDateTime || !eventData.statusID) {
            return {
                status: false,
                message: 'Fill out all fields.'
            };
        }

        if (eventData.capacity && (isNaN(eventData.capacity) || parseInt(eventData.capacity, 10) <= 0)) {
            return {
                status: false,
                message: 'Capacity must be a positive number.'
            };
        }

        if (new Date(eventData.endDateTime) < new Date(eventData.startDateTime)) {
            return {
                status: false,
                message: 'End date and time must be after the start.'
            };
        }

        return {
            status: true
        };
    }

    function formatDateTimeForInput(dateTime) {
        return dateTime ? dateTime.replace(' ', 'T').slice(0, 16) : '';
    }

    function showError(message) {
        Swal.fire({
            title: 'Error!',
            text: message,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
});
