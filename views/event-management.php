<?php
    session_start();

    require_once "../bl/EventManager.php";
    require_once "../bl/EventCategoryManager.php";
    require_once "../bl/EventStatusManager.php";

    if (
        !isset($_SESSION['permissions']) ||
        !isset($_SESSION['role_id']) ||
        (int)$_SESSION['role_id'] !== 1 ||
        !in_array('manage_users', $_SESSION['permissions']) ||
        !in_array('manage_events', $_SESSION['permissions'])
    ) {
        header("Location: unauthorized.php");
        exit;
    }

    $eventManager = new EventManager();
    $eventCategoryManager = new EventCategoryManager();
    $eventStatusManager = new EventStatusManager();

    $events = $eventManager -> getEvents(
        $_SESSION['user_id'],
        $_SESSION['role_id'],
        $_SESSION['permissions']
    );
    $categories = $eventCategoryManager -> getEventCategories();
    $statuses = $eventStatusManager -> getEventStatuses();

    $publishedCount = count(array_filter($events, function($event) {
        return $event['event_status_name'] === 'published';
    }));

    $draftCount = count(array_filter($events, function($event) {
        return $event['event_status_name'] === 'draft';
    }));

    $cancelledCount = count(array_filter($events, function($event) {
        return $event['event_status_name'] === 'cancelled';
    }));

    function formatEventDate($dateTime) {
        return date('M d, Y g:i A', strtotime($dateTime));
    }

    function statusBadgeClass($status) {
        switch($status) {
            case 'published':
                return 'bg-emerald-100 text-emerald-700';
            case 'cancelled':
                return 'bg-red-100 text-red-700';
            default:
                return 'bg-amber-100 text-amber-700';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management - TRACK</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../public/output.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../script/swal-theme.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen flex flex-col font-body bg-ust-light-bg text-ust-dark">
    <?php
        $activePage = 'management';
        $showManagementLink = true;
        require __DIR__ . '/partials/navbar.php';
    ?>

    <div class="mx-auto flex w-full max-w-7xl flex-1 flex-col gap-6 px-6 py-8 lg:flex-row lg:items-start">
        <?php
            $managementArea = 'events';
            $managementSections = [
                ['id' => 'overview', 'label' => 'Overview', 'icon' => 'fa-chart-line'],
                ['id' => 'create-event', 'label' => 'Create Event', 'icon' => 'fa-plus'],
                ['id' => 'manage-events', 'label' => 'Manage Events', 'icon' => 'fa-calendar-check'],
            ];
            require __DIR__ . '/partials/management-sidebar.php';
        ?>

        <main class="min-w-0 flex-1">
            <section id="overview" class="scroll-mt-6">
                <div class="mb-8">
                    <div class="mb-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-ust-gold">Admin Workspace</p>
                        <h1 class="mt-2 font-heading text-3xl font-bold text-ust-dark">Event Management</h1>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="summary-card bg-white rounded-lg shadow-ust p-6 border border-gray-100">
                            <div class="text-4xl font-heading font-bold text-ust-gold mb-2"><?= count($events) ?></div>
                            <div class="text-sm text-ust-gray font-semibold flex items-center gap-2">
                                <i class="fas fa-calendar-days"></i>Total Events
                            </div>
                        </div>
                        <div class="summary-card bg-white rounded-lg shadow-ust p-6 border border-gray-100">
                            <div class="text-4xl font-heading font-bold text-ust-dark mb-2"><?= $publishedCount ?></div>
                            <div class="text-sm text-ust-gray font-semibold flex items-center gap-2">
                                <i class="fas fa-bullhorn"></i>Published
                            </div>
                        </div>
                        <div class="summary-card bg-white rounded-lg shadow-ust p-6 border border-gray-100">
                            <div class="text-4xl font-heading font-bold text-ust-dark mb-2"><?= $draftCount ?></div>
                            <div class="text-sm text-ust-gray font-semibold flex items-center gap-2">
                                <i class="fas fa-pen-ruler"></i>Drafts
                            </div>
                        </div>
                        <div class="summary-card bg-white rounded-lg shadow-ust p-6 border border-gray-100">
                            <div class="text-4xl font-heading font-bold text-ust-dark mb-2"><?= $cancelledCount ?></div>
                            <div class="text-sm text-ust-gray font-semibold flex items-center gap-2">
                                <i class="fas fa-ban"></i>Cancelled
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="create-event" class="scroll-mt-6 bg-white shadow-ust-md rounded-2xl border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-ust-dark to-ust-gray px-6 py-5 border-b-4 border-ust-gold">
                    <div class="flex items-center justify-between gap-4 flex-wrap">
                        <div>
                            <p class="text-xs uppercase tracking-[0.25em] text-ust-gold">Event Console</p>
                            <h2 class="text-2xl font-heading font-bold text-white mt-2">Create a New Event</h2>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-xs font-semibold text-white">
                            <i class="fas fa-shield-alt text-ust-gold"></i>
                            Admin Access
                        </span>
                    </div>
                </div>

                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="title" class="block text-sm font-semibold text-ust-dark mb-2">Event Title</label>
                            <input type="text" id="title" class="create-event-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream" placeholder="Enter event title">
                        </div>

                        <div>
                            <label for="categoryID" class="block text-sm font-semibold text-ust-dark mb-2">Category</label>
                            <select id="categoryID" class="create-event-field w-full rounded-lg border-2 border-gray-200 bg-ust-cream px-4 py-3 text-sm text-ust-dark focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition">
                                <option value="">Choose Category</option>
                                <?php foreach($categories as $category) : ?>
                                    <option value="<?= $category['event_category_id'] ?>">
                                        <?= htmlspecialchars($category['event_category_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-semibold text-ust-dark mb-2">Location</label>
                            <input type="text" id="location" class="create-event-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream" placeholder="Venue or room">
                        </div>

                        <div>
                            <label for="capacity" class="block text-sm font-semibold text-ust-dark mb-2">Capacity (optional)</label>
                            <input type="text" inputmode="numeric" pattern="[0-9]*" id="capacity" class="create-event-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream" placeholder="0">
                        </div>

                        <div>
                            <label for="startDateTime" class="block text-sm font-semibold text-ust-dark mb-2">Start Date & Time</label>
                            <input type="datetime-local" id="startDateTime" class="create-event-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm text-ust-dark focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream">
                        </div>

                        <div>
                            <label for="endDateTime" class="block text-sm font-semibold text-ust-dark mb-2">End Date & Time</label>
                            <input type="datetime-local" id="endDateTime" class="create-event-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm text-ust-dark focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream">
                        </div>

                        <div class="md:col-span-2">
                            <label for="statusID" class="block text-sm font-semibold text-ust-dark mb-2">Status</label>
                            <select id="statusID" class="create-event-field w-full rounded-lg border-2 border-gray-200 bg-ust-cream px-4 py-3 text-sm text-ust-dark focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition">
                                <option value="">Choose Status</option>
                                <?php foreach($statuses as $status) : ?>
                                    <option value="<?= $status['event_status_id'] ?>" <?= $status['event_status_name'] === 'draft' ? 'selected' : '' ?>>
                                        <?= ucfirst(htmlspecialchars($status['event_status_name'])) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-semibold text-ust-dark mb-2">Description</label>
                            <div class="relative">
                                <textarea id="description" maxlength="300" rows="5" class="create-event-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 pb-9 text-sm text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream resize-none" placeholder="Describe the event, who it is for, and what attendees should expect."></textarea>
                                <span id="descriptionCounter" class="pointer-events-none absolute bottom-3 left-4 text-xs font-semibold text-ust-gray">300 characters left</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                        <button id="clearEventForm" type="button" class="px-6 py-3 rounded-lg border-2 border-ust-gold text-ust-gold hover:bg-ust-gold/5 text-sm font-semibold transition duration-200">
                            Clear
                        </button>
                        <button id="createEventBtn" type="button" class="px-6 py-3 rounded-lg text-sm font-semibold text-white bg-ust-gold hover:bg-ust-gold-dark shadow-ust transition duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-plus"></i>
                            Create Event
                        </button>
                    </div>
                </div>
            </section>

            <section id="manage-events" class="scroll-mt-6">
                <div class="flex items-end justify-between gap-4 flex-wrap mb-6">
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-ust-gold font-semibold">Event Registry</p>
                        <h2 class="text-3xl font-heading font-bold text-ust-dark mt-2">Manage Events</h2>
                    </div>
                    <div class="text-sm text-ust-gray">
                        <?= count($events) ?> event<?= count($events) === 1 ? '' : 's' ?> available
                    </div>
                </div>

                <?php if (!empty($events)) : ?>
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                        <?php foreach($events as $event) : ?>
                            <article class="group bg-white shadow-ust rounded-2xl border border-gray-100 overflow-hidden hover:shadow-ust-md transition">
                                <div class="h-2 bg-gradient-to-r from-ust-gold via-[#FFE48C] to-ust-gold"></div>
                                <div class="p-6">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <div class="flex flex-wrap items-center gap-2 mb-3">
                                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold <?= statusBadgeClass($event['event_status_name']) ?>">
                                                    <?= strtoupper(htmlspecialchars($event['event_status_name'])) ?>
                                                </span>
                                                <span class="inline-flex items-center rounded-full bg-ust-gold/10 px-3 py-1 text-xs font-semibold text-ust-dark">
                                                    <?= htmlspecialchars($event['event_category_name']) ?>
                                                </span>
                                            </div>
                                            <h3 class="text-2xl font-heading font-bold text-ust-dark leading-tight">
                                                <?= htmlspecialchars($event['title']) ?>
                                            </h3>
                                        </div>
                                        <div class="text-right text-xs text-ust-gray whitespace-nowrap">
                                            <p>Created by</p>
                                            <p class="mt-1 font-semibold text-ust-dark">
                                                <?= htmlspecialchars($event['first_name'] . ' ' . $event['last_name']) ?>
                                            </p>
                                        </div>
                                    </div>

                                    <p class="mt-4 text-sm text-ust-gray leading-7 min-h-[84px]">
                                        <?= nl2br(htmlspecialchars($event['description'])) ?>
                                    </p>

                                    <div class="mt-6 grid sm:grid-cols-2 gap-4">
                                        <div class="rounded-xl bg-ust-cream border border-ust-gold/10 p-4">
                                            <p class="text-xs uppercase tracking-[0.2em] text-ust-gray">Schedule</p>
                                            <p class="mt-2 text-sm font-semibold text-ust-dark"><?= formatEventDate($event['start_datetime']) ?></p>
                                            <p class="mt-1 text-xs text-ust-gray">to <?= formatEventDate($event['end_datetime']) ?></p>
                                        </div>
                                        <div class="rounded-xl bg-ust-cream border border-ust-gold/10 p-4">
                                            <p class="text-xs uppercase tracking-[0.2em] text-ust-gray">Venue & Capacity</p>
                                            <p class="mt-2 text-sm font-semibold text-ust-dark"><?= htmlspecialchars($event['location']) ?></p>
                                            <p class="mt-1 text-xs text-ust-gray">
                                                Capacity: <?= $event['capacity'] ? (int)$event['capacity'] : 'Open / not set' ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex items-center justify-between gap-4 flex-wrap">
                                        <div class="text-xs text-ust-gray">
                                            Updated <?= formatEventDate($event['updated_at']) ?>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button
                                                type="button"
                                                class="event-action-btn flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-ust-gold hover:bg-ust-gold-dark rounded-lg transition"
                                                data-action="edit"
                                                data-eventid="<?= $event['event_id'] ?>"
                                                data-title="<?= htmlspecialchars($event['title'], ENT_QUOTES) ?>"
                                                data-description="<?= htmlspecialchars($event['description'], ENT_QUOTES) ?>"
                                                data-categoryid="<?= $event['event_category_id'] ?>"
                                                data-location="<?= htmlspecialchars($event['location'], ENT_QUOTES) ?>"
                                                data-capacity="<?= htmlspecialchars((string)$event['capacity'], ENT_QUOTES) ?>"
                                                data-startdatetime="<?= htmlspecialchars($event['start_datetime'], ENT_QUOTES) ?>"
                                                data-enddatetime="<?= htmlspecialchars($event['end_datetime'], ENT_QUOTES) ?>"
                                                data-statusid="<?= $event['event_status_id'] ?>"
                                            >
                                                <i class="fas fa-pen"></i>Edit
                                            </button>
                                            <button
                                                type="button"
                                                class="event-action-btn flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-lg transition"
                                                data-action="delete"
                                                data-eventid="<?= $event['event_id'] ?>"
                                            >
                                                <i class="fas fa-trash"></i>Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div class="bg-white rounded-2xl border border-dashed border-ust-gold/40 shadow-ust p-10 text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-ust-gold/10 text-ust-gold flex items-center justify-center mb-5">
                            <i class="fas fa-calendar-times text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-heading font-bold text-ust-dark">No events available yet</h3>
                        <p class="mt-3 text-sm text-ust-gray max-w-xl mx-auto leading-7">
                            Start by creating a draft or publishing the first school event from the admin console above.
                        </p>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <?php require __DIR__ . '/partials/footer.php'; ?>

    <div id="editEventModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-ust-md max-w-3xl w-full max-h-[90vh] overflow-y-auto border-t-4 border-ust-gold">
            <div class="sticky top-0 bg-gradient-to-r from-ust-dark to-ust-gray border-b-4 border-ust-gold px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-heading font-bold text-white flex items-center gap-2">
                    <i class="fas fa-calendar-check"></i>Edit Event
                </h3>
                <button id="closeEditEventModal" type="button" class="text-white hover:text-ust-gold transition">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <div class="p-6">
                <input type="hidden" id="edit_eventID">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="edit_title" class="block text-sm font-semibold text-ust-dark mb-2">Event Title</label>
                        <input type="text" id="edit_title" class="edit-event-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream">
                    </div>

                    <div>
                        <label for="edit_categoryID" class="block text-sm font-semibold text-ust-dark mb-2">Category</label>
                        <select id="edit_categoryID" class="edit-event-field w-full rounded-lg border-2 border-gray-200 bg-ust-cream px-4 py-3 text-sm text-ust-dark focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition">
                            <option value="">Choose Category</option>
                            <?php foreach($categories as $category) : ?>
                                <option value="<?= $category['event_category_id'] ?>">
                                    <?= htmlspecialchars($category['event_category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="edit_location" class="block text-sm font-semibold text-ust-dark mb-2">Location</label>
                        <input type="text" id="edit_location" class="edit-event-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream">
                    </div>

                    <div>
                        <label for="edit_capacity" class="block text-sm font-semibold text-ust-dark mb-2">Capacity (optional)</label>
                        <input type="number" id="edit_capacity" class="edit-event-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream">
                    </div>

                    <div>
                        <label for="edit_startDateTime" class="block text-sm font-semibold text-ust-dark mb-2">Start Date & Time</label>
                        <input type="datetime-local" id="edit_startDateTime" class="edit-event-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm text-ust-dark focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream">
                    </div>

                    <div>
                        <label for="edit_endDateTime" class="block text-sm font-semibold text-ust-dark mb-2">End Date & Time</label>
                        <input type="datetime-local" id="edit_endDateTime" class="edit-event-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm text-ust-dark focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream">
                    </div>

                    <div class="md:col-span-2">
                        <label for="edit_statusID" class="block text-sm font-semibold text-ust-dark mb-2">Status</label>
                        <select id="edit_statusID" class="edit-event-field w-full rounded-lg border-2 border-gray-200 bg-ust-cream px-4 py-3 text-sm text-ust-dark focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition">
                            <option value="">Choose Status</option>
                            <?php foreach($statuses as $status) : ?>
                                <option value="<?= $status['event_status_id'] ?>">
                                    <?= ucfirst(htmlspecialchars($status['event_status_name'])) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="edit_description" class="block text-sm font-semibold text-ust-dark mb-2">Description</label>
                        <textarea id="edit_description" rows="5" class="edit-event-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream resize-none"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                    <button id="cancelEditEventBtn" type="button" class="px-6 py-3 rounded-lg border-2 border-ust-gold text-ust-gold hover:bg-ust-gold/5 text-sm font-semibold transition duration-200">
                        Cancel
                    </button>
                    <button id="updateEventBtn" type="button" class="px-6 py-3 rounded-lg text-sm font-semibold text-white bg-ust-gold hover:bg-ust-gold-dark shadow-ust transition duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="../script/utils.js"></script>
    <script src="../script/events.js"></script>
    <script src="../script/management-nav.js"></script>
</body>
</html>
