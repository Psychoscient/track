document.addEventListener('DOMContentLoaded', function() {
    const logoutBtn = document.getElementById('logout');
    const createEventBtn = document.getElementById('createEventBtn');
    const clearEventFormBtn = document.getElementById('clearEventForm');
    const createStartDateTime = document.getElementById('startDateTime');
    const createEndDateTime = document.getElementById('endDateTime');
    const createDescription = document.getElementById('description');
    const createDescriptionCounter = document.getElementById('descriptionCounter');
    const createVenue = document.getElementById('eventVenueID');
    const createCapacityDisplay = document.getElementById('capacityDisplay');
    const editEventModal = document.getElementById('editEventModal');
    const editVenue = document.getElementById('edit_eventVenueID');
    const editCapacityDisplay = document.getElementById('edit_capacityDisplay');
    const updateEventBtn = document.getElementById('updateEventBtn');
    const closeEditEventModal = document.getElementById('closeEditEventModal');
    const cancelEditEventBtn = document.getElementById('cancelEditEventBtn');
    const actionButtons = document.querySelectorAll('.event-action-btn');
    const openEventChartsBtn = document.getElementById('openEventChartsBtn');
    const eventChartModal = document.getElementById('eventChartModal');
    const closeEventChartButtons = document.querySelectorAll('[data-close-event-charts]');
    const eventAnalyticsData = window.eventAnalyticsData || null;
    const chartPalette = ['#F4C300', '#1A1A1A', '#D4A400', '#FED766', '#333333', '#B88900', '#F8E08E'];
    const compactNumberFormatter = new Intl.NumberFormat('en-US');
    let eventStatusChartInstance = null;
    let eventCategoryChartInstance = null;
    let eventTimelineChartInstance = null;
    let eventScheduleChartInstance = null;
    let eventVenueChartInstance = null;

    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            Utils.logout();
        });
    }

    if (openEventChartsBtn) {
        openEventChartsBtn.addEventListener('click', function() {
            openEventChartModal();
        });
    }

    closeEventChartButtons.forEach((button) => {
        button.addEventListener('click', closeEventChartModal);
    });

    if (eventChartModal) {
        eventChartModal.addEventListener('click', function(e) {
            if (e.target === eventChartModal) {
                closeEventChartModal();
            }
        });
    }

    initializeCreateDateConstraints();
    initializeCreateDescriptionCounter();
    initializeVenueCapacityFields();
    initializeManagedCards({
        listId: 'eventManagementList',
        searchInputId: 'eventTableSearch',
        summaryId: 'eventTableSummary',
        paginationId: 'eventTablePagination',
        itemLabel: 'event',
        emptyMessage: 'No matching events found.'
    });

    if (clearEventFormBtn) {
        clearEventFormBtn.addEventListener('click', function() {
            resetCreateEventForm();
        });
    }

    if (createEventBtn) {
        createEventBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const eventData = getCreateEventData();
            const validation = validateEventData(eventData, true);

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
                    eventVenueID: eventData.eventVenueID,
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
            const validation = validateEventData(eventData, false);

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
                    eventVenueID: eventData.eventVenueID,
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

        updateCreateDateConstraints();
        updateCreateDescriptionCounter();
        updateVenueCapacityDisplay(createVenue, createCapacityDisplay);
    }

    function openEditEventModal(eventData) {
        if (!editEventModal) {
            return;
        }

        document.getElementById('edit_eventID').value = eventData.eventid || '';
        document.getElementById('edit_title').value = eventData.title || '';
        document.getElementById('edit_description').value = eventData.description || '';
        document.getElementById('edit_categoryID').value = eventData.categoryid || '';
        document.getElementById('edit_eventVenueID').value = eventData.eventvenueid || '';
        updateVenueCapacityDisplay(editVenue, editCapacityDisplay);
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
            eventVenueID: document.getElementById('eventVenueID') ? document.getElementById('eventVenueID').value : '',
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
            eventVenueID: document.getElementById('edit_eventVenueID') ? document.getElementById('edit_eventVenueID').value : '',
            startDateTime: document.getElementById('edit_startDateTime') ? document.getElementById('edit_startDateTime').value : '',
            endDateTime: document.getElementById('edit_endDateTime') ? document.getElementById('edit_endDateTime').value : '',
            statusID: document.getElementById('edit_statusID') ? document.getElementById('edit_statusID').value : ''
        };
    }

    function initializeCreateDateConstraints() {
        if (!createStartDateTime || !createEndDateTime) {
            return;
        }

        updateCreateDateConstraints();

        createStartDateTime.addEventListener('input', function() {
            updateCreateDateConstraints();
        });

        window.setInterval(updateCreateDateConstraints, 60000);
    }

    function updateCreateDateConstraints() {
        if (!createStartDateTime || !createEndDateTime) {
            return;
        }

        const currentDateTime = getLocalDateTimeValue(new Date());
        const earliestEndDateTime = createStartDateTime.value && createStartDateTime.value > currentDateTime
            ? createStartDateTime.value
            : currentDateTime;

        createStartDateTime.min = currentDateTime;
        createEndDateTime.min = earliestEndDateTime;

        if (createEndDateTime.value && createEndDateTime.value < earliestEndDateTime) {
            createEndDateTime.value = '';
        }
    }

    function initializeCreateDescriptionCounter() {
        if (!createDescription || !createDescriptionCounter) {
            return;
        }

        updateCreateDescriptionCounter();
        createDescription.addEventListener('input', updateCreateDescriptionCounter);
    }

    function updateCreateDescriptionCounter() {
        if (!createDescription || !createDescriptionCounter) {
            return;
        }

        const remainingCharacters = Math.max(0, 300 - createDescription.value.length);
        createDescriptionCounter.textContent = `${remainingCharacters} character${remainingCharacters === 1 ? '' : 's'} left`;
    }

    function initializeVenueCapacityFields() {
        if (createVenue) {
            updateVenueCapacityDisplay(createVenue, createCapacityDisplay);
            createVenue.addEventListener('change', function() {
                updateVenueCapacityDisplay(createVenue, createCapacityDisplay);
            });
        }

        if (editVenue) {
            editVenue.addEventListener('change', function() {
                updateVenueCapacityDisplay(editVenue, editCapacityDisplay);
            });
        }
    }

    function updateVenueCapacityDisplay(venueSelect, capacityDisplay) {
        if (!venueSelect || !capacityDisplay) {
            return;
        }

        const selectedOption = venueSelect.options[venueSelect.selectedIndex];
        capacityDisplay.value = selectedOption && selectedOption.dataset.capacity
            ? selectedOption.dataset.capacity
            : '';
    }

    function validateEventData(eventData, rejectPastStart) {
        if (!eventData.title || !eventData.description || !eventData.categoryID || !eventData.eventVenueID || !eventData.startDateTime || !eventData.endDateTime || !eventData.statusID) {
            return {
                status: false,
                message: 'Fill out all fields.'
            };
        }

        if (rejectPastStart && eventData.description.length > 300) {
            return {
                status: false,
                message: 'Description must be 300 characters or fewer.'
            };
        }

        if (rejectPastStart && eventData.startDateTime < getLocalDateTimeValue(new Date())) {
            return {
                status: false,
                message: 'Start date and time cannot be before the current time.'
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

    function getLocalDateTimeValue(date) {
        const localDate = new Date(date.getTime() - (date.getTimezoneOffset() * 60000));
        return localDate.toISOString().slice(0, 16);
    }

    function initializeManagedCards(config) {
        const list = document.getElementById(config.listId);
        const searchInput = document.getElementById(config.searchInputId);
        const summary = document.getElementById(config.summaryId);
        const pagination = document.getElementById(config.paginationId);

        if (!list || !searchInput || !summary || !pagination) {
            return;
        }

        const prevButton = pagination.querySelector('[data-pagination-prev]');
        const nextButton = pagination.querySelector('[data-pagination-next]');
        const pageLabel = pagination.querySelector('[data-pagination-pages]');
        const paginationStatus = pagination.querySelector('[data-pagination-status]');
        const pageSize = Number(list.dataset.pageSize) || 6;
        const dataRows = Array.from(list.querySelectorAll('[data-list-row]'));
        const noResultsCard = createNoResultsCard(config.emptyMessage);
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
            noResultsCard.remove();

            if (totalRows === 0) {
                list.appendChild(noResultsCard);
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

    function createNoResultsCard(message) {
        const card = document.createElement('div');
        card.className = 'rounded-2xl border border-dashed border-ust-gold/40 bg-ust-cream p-10 text-center text-ust-gray';
        card.style.gridColumn = '1 / -1';
        card.innerHTML = `
            <i class="fas fa-search text-2xl mb-2 block opacity-50"></i>
            ${message}
        `;

        return card;
    }

    function openEventChartModal() {
        if (!eventChartModal) {
            return;
        }

        eventChartModal.classList.remove('hidden');
        window.setTimeout(initializeEventCharts, 300);
    }

    function closeEventChartModal() {
        if (!eventChartModal) {
            return;
        }

        eventChartModal.classList.add('hidden');
        destroyEventCharts();
    }

    function updateEventAnalyticsSummary() {
        if (!eventAnalyticsData) {
            return;
        }

        setText('[data-event-analytics-total]', eventAnalyticsData.summary.totalEvents);
        setText('[data-event-analytics-published]', eventAnalyticsData.summary.publishedEvents);
        setText('[data-event-analytics-month]', eventAnalyticsData.summary.eventsThisMonth);
        setText('[data-event-analytics-upcoming]', eventAnalyticsData.summary.upcomingEvents);
        setText('[data-event-analytics-capacity]', compactNumberFormatter.format(eventAnalyticsData.summary.totalCapacity));
    }

    function setText(selector, value) {
        const element = document.querySelector(selector);

        if (element) {
            element.textContent = value;
        }
    }

    function chartBaseOptions(extraOptions = {}) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        boxHeight: 12,
                        color: '#1a1a1a',
                        font: {
                            family: "'Outfit', sans-serif",
                            size: 12,
                            weight: '600'
                        },
                        padding: 16
                    }
                },
                tooltip: {
                    backgroundColor: '#1a1a1a',
                    titleColor: '#F4C300',
                    bodyColor: '#ffffff',
                    borderColor: '#F4C300',
                    borderWidth: 1,
                    titleFont: {
                        family: "'Outfit', sans-serif",
                        size: 14,
                        weight: '700'
                    },
                    bodyFont: {
                        family: "'Inter', sans-serif",
                        size: 13
                    },
                    padding: 12,
                    cornerRadius: 10
                }
            },
            ...extraOptions
        };
    }

    function axisOptions(indexAxis = 'x') {
        const valueAxis = indexAxis === 'y' ? 'x' : 'y';
        const categoryAxis = indexAxis === 'y' ? 'y' : 'x';

        return {
            indexAxis,
            scales: {
                [valueAxis]: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        color: '#6b7280',
                        font: {
                            family: "'Inter', sans-serif",
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(26, 26, 26, 0.08)'
                    }
                },
                [categoryAxis]: {
                    ticks: {
                        color: '#1a1a1a',
                        font: {
                            family: "'Outfit', sans-serif",
                            size: 12,
                            weight: '700'
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        };
    }

    function initializeEventCharts() {
        if (!eventAnalyticsData) {
            return;
        }

        destroyEventCharts();
        updateEventAnalyticsSummary();

        if (typeof Chart === 'undefined') {
            return;
        }

        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#333333';

        const statusLabels = Object.keys(eventAnalyticsData.statuses);
        const statusValues = Object.values(eventAnalyticsData.statuses);
        const statusCtx = document.getElementById('eventStatusChart').getContext('2d');
        eventStatusChartInstance = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: chartPalette.slice(0, statusLabels.length),
                    borderColor: '#ffffff',
                    borderWidth: 4,
                    hoverOffset: 12
                }]
            },
            options: chartBaseOptions({
                cutout: '62%'
            })
        });

        const categoryLabels = Object.keys(eventAnalyticsData.categories);
        const categoryValues = Object.values(eventAnalyticsData.categories);
        const categoryCtx = document.getElementById('eventCategoryChart').getContext('2d');
        eventCategoryChartInstance = new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Events',
                    data: categoryValues,
                    backgroundColor: '#F4C300',
                    borderColor: '#D4A400',
                    borderWidth: 2,
                    borderRadius: 10,
                    maxBarThickness: 42
                }]
            },
            options: chartBaseOptions({
                plugins: {
                    ...chartBaseOptions().plugins,
                    legend: {
                        display: false
                    }
                },
                ...axisOptions('y')
            })
        });

        const timelineCtx = document.getElementById('eventTimelineChart').getContext('2d');
        const timelineGradient = timelineCtx.createLinearGradient(0, 0, 0, 260);
        timelineGradient.addColorStop(0, 'rgba(244, 195, 0, 0.35)');
        timelineGradient.addColorStop(1, 'rgba(244, 195, 0, 0.03)');
        eventTimelineChartInstance = new Chart(timelineCtx, {
            type: 'line',
            data: {
                labels: eventAnalyticsData.timeline.map((month) => month.label),
                datasets: [{
                    label: 'Events',
                    data: eventAnalyticsData.timeline.map((month) => month.count),
                    fill: true,
                    backgroundColor: timelineGradient,
                    borderColor: '#D4A400',
                    borderWidth: 3,
                    pointBackgroundColor: '#1A1A1A',
                    pointBorderColor: '#F4C300',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.38
                }]
            },
            options: chartBaseOptions({
                plugins: {
                    ...chartBaseOptions().plugins,
                    legend: {
                        display: false
                    }
                },
                scales: axisOptions('x').scales
            })
        });

        const scheduleLabels = Object.keys(eventAnalyticsData.schedule);
        const scheduleValues = Object.values(eventAnalyticsData.schedule);
        const scheduleCtx = document.getElementById('eventScheduleChart').getContext('2d');
        eventScheduleChartInstance = new Chart(scheduleCtx, {
            type: 'polarArea',
            data: {
                labels: scheduleLabels,
                datasets: [{
                    data: scheduleValues,
                    backgroundColor: ['rgba(244, 195, 0, 0.82)', 'rgba(26, 26, 26, 0.82)', 'rgba(212, 164, 0, 0.72)'],
                    borderColor: '#ffffff',
                    borderWidth: 3
                }]
            },
            options: chartBaseOptions({
                scales: {
                    r: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            backdropColor: 'transparent',
                            color: '#6b7280'
                        },
                        grid: {
                            color: 'rgba(26, 26, 26, 0.08)'
                        },
                        angleLines: {
                            color: 'rgba(26, 26, 26, 0.08)'
                        }
                    }
                }
            })
        });

        const venueLabels = Object.keys(eventAnalyticsData.venues);
        const venueValues = Object.values(eventAnalyticsData.venues);
        const venueCtx = document.getElementById('eventVenueChart').getContext('2d');
        eventVenueChartInstance = new Chart(venueCtx, {
            type: 'bar',
            data: {
                labels: venueLabels,
                datasets: [{
                    label: 'Events',
                    data: venueValues,
                    backgroundColor: chartPalette.map((color, index) => index % 2 === 0 ? color : '#1A1A1A'),
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    borderRadius: 10,
                    maxBarThickness: 48
                }]
            },
            options: chartBaseOptions({
                plugins: {
                    ...chartBaseOptions().plugins,
                    legend: {
                        display: false
                    }
                },
                scales: axisOptions('x').scales
            })
        });
    }

    function destroyEventCharts() {
        if (eventStatusChartInstance) {
            eventStatusChartInstance.destroy();
            eventStatusChartInstance = null;
        }
        if (eventCategoryChartInstance) {
            eventCategoryChartInstance.destroy();
            eventCategoryChartInstance = null;
        }
        if (eventTimelineChartInstance) {
            eventTimelineChartInstance.destroy();
            eventTimelineChartInstance = null;
        }
        if (eventScheduleChartInstance) {
            eventScheduleChartInstance.destroy();
            eventScheduleChartInstance = null;
        }
        if (eventVenueChartInstance) {
            eventVenueChartInstance.destroy();
            eventVenueChartInstance = null;
        }
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
