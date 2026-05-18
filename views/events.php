<?php
    session_start();

    require_once "../bl/EventManager.php";
    require_once "../bl/EventCategoryManager.php";
    require_once "../bl/EventStatusManager.php";
    require_once "../bl/EventVenueManager.php";
    require_once "../bl/UserManager.php";
    require_once "../bl/RoleManager.php";

    if (!isset($_SESSION['permissions'])) {
        header("Location: unauthorized.php");
        exit;
    }

    $userManager = new UserManager();
    $roleManager = new RoleManager();

    $currentUser = $userManager -> getUser("user_id", $_SESSION['user_id']);
    if ($currentUser && (!isset($currentUser['status']) || $currentUser['status'] !== false)) {
        $permissions = array_column($roleManager -> getPermissions($currentUser['role_id']), 'permission_name');
        $_SESSION['role_id'] = $currentUser['role_id'];
        $_SESSION['permissions'] = $permissions;
    }

    $eventManager = new EventManager();
    $eventCategoryManager = new EventCategoryManager();
    $eventStatusManager = new EventStatusManager();
    $eventVenueManager = new EventVenueManager();

    $events = $eventManager -> getEvents(
        $_SESSION['user_id'],
        $_SESSION['role_id'],
        $_SESSION['permissions']
    );
    $categories = $eventCategoryManager -> getEventCategories();
    $statuses = $eventStatusManager -> getEventStatuses();
    $venues = $eventVenueManager -> getEventVenues();

    $canManageEvents = in_array('manage_events', $_SESSION['permissions']);
    $canManageUsers = in_array('manage_users', $_SESSION['permissions']);
    $isAdmin = (int)$_SESSION['role_id'] === 1;

    $publishedCount = count(array_filter($events, function($event) {
        return $event['event_status_name'] === 'published';
    }));

    $myManagedCount = count(array_filter($events, function($event) {
        return isset($_SESSION['user_id']) && (int)$event['created_by'] === (int)$_SESSION['user_id'];
    }));

    $draftCount = count(array_filter($events, function($event) {
        return $event['event_status_name'] === 'draft';
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
    <title>Events - TRACK</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../public/output.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../script/swal-theme.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen flex flex-col font-body bg-ust-light-bg text-ust-dark">

    <?php
        $activePage = 'events';
        $showManagementLink = $canManageUsers;
        require __DIR__ . '/partials/navbar.php';
    ?>

    <section class="relative overflow-hidden bg-gradient-to-br from-ust-dark via-[#2B2B2B] to-ust-gray text-white">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute -top-10 left-10 h-40 w-40 rounded-full border border-ust-gold/50"></div>
            <div class="absolute bottom-8 right-12 h-56 w-56 rounded-full bg-ust-gold/10 blur-3xl"></div>
            <div class="absolute top-1/2 right-1/4 h-px w-40 bg-gradient-to-r from-transparent via-ust-gold to-transparent"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-6 py-16">
            <div class="grid lg:grid-cols-[1.3fr_0.9fr] gap-10 items-center">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-ust-gold/30 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-ust-gold">
                        <i class="fas fa-calendar-week"></i>
                        Event Center
                    </span>
                    <h2 class="mt-6 max-w-3xl text-4xl md:text-5xl font-heading font-bold leading-tight">
                        Plan, publish, and track campus events in one shared workspace.
                    </h2>
                    <p class="mt-4 max-w-2xl text-sm md:text-base text-gray-200 leading-7">
                        Browse live school activities, keep organizers accountable for their own schedules, and manage event publishing with the same clean workflow already used across the system.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="#eventsList" class="bg-ust-gold text-ust-dark px-8 py-3 rounded-lg font-semibold shadow-ust hover:bg-ust-gold-dark transition">
                            <i class="fas fa-stream mr-2"></i>Browse Events
                        </a>
                        <?php if ($canManageEvents) : ?>
                            <a href="#createEventPanel" class="border border-white/30 bg-white/10 px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-ust-dark transition">
                                <i class="fas fa-plus mr-2"></i>Create Event
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-1 gap-4">
                    <div class="bg-white/10 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
                        <p class="text-xs uppercase tracking-[0.2em] text-gray-300">Published Events</p>
                        <p class="mt-3 text-4xl font-heading font-bold text-ust-gold"><?= $publishedCount ?></p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
                        <p class="text-xs uppercase tracking-[0.2em] text-gray-300"><?= $canManageEvents ? 'Your Managed Events' : 'Visible Events' ?></p>
                        <p class="mt-3 text-4xl font-heading font-bold text-white"><?= $canManageEvents ? $myManagedCount : count($events) ?></p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm border border-white/10 rounded-2xl p-5">
                        <p class="text-xs uppercase tracking-[0.2em] text-gray-300">Draft Queue</p>
                        <p class="mt-3 text-4xl font-heading font-bold text-white"><?= $draftCount ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-6 py-10">
        <?php if ($canManageEvents) : ?>
            <section id="createEventPanel" class="bg-white shadow-ust-md rounded-2xl border border-gray-100 overflow-hidden mb-10">
                <div class="bg-gradient-to-r from-ust-dark to-ust-gray px-6 py-5 border-b-4 border-ust-gold">
                    <div class="flex items-center justify-between gap-4 flex-wrap">
                        <div>
                            <p class="text-xs uppercase tracking-[0.25em] text-ust-gold">Organizer Console</p>
                            <h3 class="text-2xl font-heading font-bold text-white mt-2">Create a New Event</h3>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-xs font-semibold text-white">
                            <i class="fas fa-shield-alt text-ust-gold"></i>
                            <?= $isAdmin ? 'Admin Access' : 'Organizer Access' ?>
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
                            <label for="eventVenueID" class="block text-sm font-semibold text-ust-dark mb-2">Venue</label>
                            <select id="eventVenueID" class="create-event-field w-full rounded-lg border-2 border-gray-200 bg-ust-cream px-4 py-3 text-sm text-ust-dark focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition">
                                <option value="">Choose Venue</option>
                                <?php foreach($venues as $venue) : ?>
                                    <option
                                        value="<?= $venue['event_venue_id'] ?>"
                                        data-capacity="<?= $venue['estimated_capacity'] ?>"
                                        data-location="<?= htmlspecialchars($venue['event_venue_location'], ENT_QUOTES) ?>"
                                    >
                                        <?= htmlspecialchars($venue['event_venue_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="capacityDisplay" class="block text-sm font-semibold text-ust-dark mb-2">Estimated Capacity</label>
                            <input type="text" id="capacityDisplay" class="w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm text-ust-dark bg-gray-100" placeholder="Select a venue" readonly>
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
        <?php endif; ?>

        <section id="eventsList">
            <div class="flex items-end justify-between gap-4 flex-wrap mb-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-ust-gold font-semibold">Schedule Board</p>
                    <h3 class="text-3xl font-heading font-bold text-ust-dark mt-2">
                        <?= $canManageEvents ? 'Event Feed and Ownership View' : 'Published School Events' ?>
                    </h3>
                </div>
                <div class="text-sm text-ust-gray">
                    <?= count($events) ?> event<?= count($events) === 1 ? '' : 's' ?> available
                </div>
            </div>

            <?php if (!empty($events)) : ?>
                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-ust">
                    <div class="management-table-toolbar">
                        <label for="eventTableSearch" class="sr-only">Search events</label>
                        <div class="management-table-search">
                            <i class="fas fa-search" aria-hidden="true"></i>
                            <input
                                id="eventTableSearch"
                                type="search"
                                placeholder="Search events"
                                class="input-field"
                            >
                        </div>
                        <p id="eventTableSummary" class="management-table-summary"></p>
                    </div>

                    <div id="eventManagementList" class="grid grid-cols-1 gap-6 p-6 xl:grid-cols-2" data-page-size="6">
                        <?php foreach($events as $event) : ?>
                            <?php
                                $isOwner = (int)$event['created_by'] === (int)$_SESSION['user_id'];
                                $canEditThisEvent = $canManageEvents && ($isAdmin || $isOwner);
                            ?>
                            <article class="group bg-white shadow-ust rounded-2xl border border-gray-100 overflow-hidden hover:shadow-ust-md transition" data-list-row>
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
                                            <?php if ($canManageEvents && $isOwner && !$isAdmin) : ?>
                                                <span class="inline-flex items-center rounded-full bg-ust-dark/5 px-3 py-1 text-xs font-semibold text-ust-dark">
                                                    Your Event
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <h4 class="text-2xl font-heading font-bold text-ust-dark leading-tight">
                                            <?= htmlspecialchars($event['title']) ?>
                                        </h4>
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
                                        <p class="mt-2 text-sm font-semibold text-ust-dark">
                                            <?= htmlspecialchars($event['event_venue_name'] ?: $event['location']) ?>
                                        </p>
                                        <?php if (!empty($event['event_venue_location'])) : ?>
                                            <p class="mt-1 text-xs text-ust-gray"><?= htmlspecialchars($event['event_venue_location']) ?></p>
                                        <?php endif; ?>
                                        <p class="mt-1 text-xs text-ust-gray">
                                            Capacity: <?= $event['estimated_capacity'] ? (int)$event['estimated_capacity'] : ($event['capacity'] ? (int)$event['capacity'] : 'Open / not set') ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-6 flex items-center justify-between gap-4 flex-wrap">
                                    <div class="text-xs text-ust-gray">
                                        Updated <?= formatEventDate($event['updated_at']) ?>
                                    </div>
                                    <?php if ($canEditThisEvent) : ?>
                                        <div class="flex items-center gap-2">
                                            <button
                                                type="button"
                                                class="event-action-btn flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-ust-gold hover:bg-ust-gold-dark rounded-lg transition"
                                                data-action="edit"
                                                data-eventid="<?= $event['event_id'] ?>"
                                                data-title="<?= htmlspecialchars($event['title'], ENT_QUOTES) ?>"
                                                data-description="<?= htmlspecialchars($event['description'], ENT_QUOTES) ?>"
                                                data-categoryid="<?= $event['event_category_id'] ?>"
                                                data-eventvenueid="<?= htmlspecialchars((string)$event['event_venue_id'], ENT_QUOTES) ?>"
                                                data-venuecapacity="<?= htmlspecialchars((string)($event['estimated_capacity'] ?? ''), ENT_QUOTES) ?>"
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
                                    <?php endif; ?>
                                </div>
                            </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                    <div id="eventTablePagination" class="flex flex-col gap-3 border-t border-gray-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <p data-pagination-status class="text-sm text-ust-gray"></p>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                data-pagination-prev
                                class="rounded-lg border-2 border-ust-gold px-4 py-2 text-sm font-semibold text-ust-gold transition hover:bg-ust-gold/5 disabled:cursor-not-allowed disabled:border-gray-200 disabled:text-gray-400 disabled:hover:bg-transparent"
                            >
                                Previous
                            </button>
                            <span data-pagination-pages class="min-w-[5rem] text-center text-sm font-semibold text-ust-dark"></span>
                            <button
                                type="button"
                                data-pagination-next
                                class="rounded-lg border-2 border-ust-gold px-4 py-2 text-sm font-semibold text-ust-gold transition hover:bg-ust-gold/5 disabled:cursor-not-allowed disabled:border-gray-200 disabled:text-gray-400 disabled:hover:bg-transparent"
                            >
                                Next
                            </button>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="bg-white rounded-2xl border border-dashed border-ust-gold/40 shadow-ust p-10 text-center">
                    <div class="w-16 h-16 mx-auto rounded-full bg-ust-gold/10 text-ust-gold flex items-center justify-center mb-5">
                        <i class="fas fa-calendar-times text-2xl"></i>
                    </div>
                    <h4 class="text-2xl font-heading font-bold text-ust-dark">No events available yet</h4>
                    <p class="mt-3 text-sm text-ust-gray max-w-xl mx-auto leading-7">
                        <?= $canManageEvents
                            ? 'Start by creating a draft or publishing your first school event from the organizer console above.'
                            : 'Published events will appear here once organizers or administrators start posting them.' ?>
                    </p>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>

    <?php if ($canManageEvents) : ?>
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
                            <label for="edit_eventVenueID" class="block text-sm font-semibold text-ust-dark mb-2">Venue</label>
                            <select id="edit_eventVenueID" class="edit-event-field w-full rounded-lg border-2 border-gray-200 bg-ust-cream px-4 py-3 text-sm text-ust-dark focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition">
                                <option value="">Choose Venue</option>
                                <?php foreach($venues as $venue) : ?>
                                    <option
                                        value="<?= $venue['event_venue_id'] ?>"
                                        data-capacity="<?= $venue['estimated_capacity'] ?>"
                                        data-location="<?= htmlspecialchars($venue['event_venue_location'], ENT_QUOTES) ?>"
                                    >
                                        <?= htmlspecialchars($venue['event_venue_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="edit_capacityDisplay" class="block text-sm font-semibold text-ust-dark mb-2">Estimated Capacity</label>
                            <input type="text" id="edit_capacityDisplay" class="w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm text-ust-dark bg-gray-100" placeholder="Select a venue" readonly>
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
    <?php endif; ?>

    <script src="../script/utils.js"></script>
    <script src="../script/events.js"></script>
</body>
</html>
