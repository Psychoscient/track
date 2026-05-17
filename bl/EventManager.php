<?php
    require_once "../model/config/Database.php";
    require_once "../model/EventModel.php";
    require_once "EventVenueManager.php";
    require_once "ErrorLoggerManager.php";

    class EventManager {
        private $eventModel;

        public function __construct() {
            $database = new Database();
            $db = $database -> connect();
            $this -> eventModel = new EventModel($db);
        }

        public function getEvents($userID, $roleID, $permissions) {
            try {
                if (!in_array('manage_events', $permissions)) {
                    $response = $this -> eventModel -> readPublishedEvents();
                    if (is_array($response)) {
                        return [];
                    }
                    return $response -> fetchAll(PDO::FETCH_ASSOC);
                }

                if ((int)$roleID === 1) {
                    $response = $this -> eventModel -> readAllEvents();
                    if (is_array($response)) {
                        return [];
                    }
                    return $response -> fetchAll(PDO::FETCH_ASSOC);
                }

                $response = $this -> eventModel -> readOrganizerEvents($userID);
                if (is_array($response)) {
                    return [];
                }
                return $response -> fetchAll(PDO::FETCH_ASSOC);

            } catch(Exception $e) {
                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(),
                    'Exception',
                    $e->getFile(),
                    $e->getLine(),
                    $_SESSION['user_id'] ?? null
                );

                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }

        public function getEvent($eventID) {
            try {
                return $this -> eventModel -> searchEvent($eventID);

            } catch(Exception $e) {
                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(),
                    'Exception',
                    $e->getFile(),
                    $e->getLine(),
                    $_SESSION['user_id'] ?? null
                );

                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }

        public function createEvent($title, $description, $categoryID, $eventVenueID, $startDateTime, $endDateTime, $statusID, $userID, $permissions) {
            try {
                if (!in_array('manage_events', $permissions)) {
                    return [
                        "status" => false,
                        "message" => "Unauthorized action."
                    ];
                }

                $venue = $this -> getValidatedVenue($eventVenueID);
                if (!$venue['status']) {
                    return $venue;
                }

                $validation = $this -> validateEvent($title, $description, $categoryID, $venue['venue']['event_venue_name'], $venue['venue']['estimated_capacity'], $startDateTime, $endDateTime, $statusID);
                if (!$validation['status']) {
                    return $validation;
                }

                $futureStartValidation = $this -> validateFutureStart($startDateTime);
                if (!$futureStartValidation['status']) {
                    return $futureStartValidation;
                }

                $createValidation = $this -> validateCreateEvent($description);
                if (!$createValidation['status']) {
                    return $createValidation;
                }

                $venueConflictValidation = $this -> validateVenueAvailability(
                    $venue['venue']['event_venue_id'],
                    $startDateTime,
                    $endDateTime
                );
                if (!$venueConflictValidation['status']) {
                    return $venueConflictValidation;
                }

                $response = $this -> eventModel -> createEvent(
                    $title,
                    $description,
                    $categoryID,
                    $venue['venue']['event_venue_id'],
                    $venue['venue']['event_venue_name'],
                    $venue['venue']['estimated_capacity'],
                    $startDateTime,
                    $endDateTime,
                    $statusID,
                    $userID
                );

                if ($response === true) {
                    return [
                        "status" => true,
                        "message" => "Event created successfully."
                    ];
                }

                if (is_array($response) && isset($response['status']) && $response['status'] === false) {
                    return $response;
                }

                return [
                    "status" => false,
                    "message" => "Failed to create event."
                ];

            } catch(Exception $e) {
                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(),
                    'Exception',
                    $e->getFile(),
                    $e->getLine(),
                    $_SESSION['user_id'] ?? null
                );

                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }

        public function updateEvent($eventID, $title, $description, $categoryID, $eventVenueID, $startDateTime, $endDateTime, $statusID, $userID, $roleID, $permissions) {
            try {
                if (!in_array('manage_events', $permissions)) {
                    return [
                        "status" => false,
                        "message" => "Unauthorized action."
                    ];
                }

                $event = $this -> eventModel -> searchEvent($eventID);
                if (!$event || (isset($event['status']) && $event['status'] === false)) {
                    return [
                        "status" => false,
                        "message" => "Event not found."
                    ];
                }

                if (!$this -> canManageEvent($event, $userID, $roleID, $permissions)) {
                    return [
                        "status" => false,
                        "message" => "You are not allowed to update this event."
                    ];
                }

                $venue = $this -> getValidatedVenue($eventVenueID);
                if (!$venue['status']) {
                    return $venue;
                }

                $validation = $this -> validateEvent($title, $description, $categoryID, $venue['venue']['event_venue_name'], $venue['venue']['estimated_capacity'], $startDateTime, $endDateTime, $statusID);
                if (!$validation['status']) {
                    return $validation;
                }

                $venueConflictValidation = $this -> validateVenueAvailability(
                    $venue['venue']['event_venue_id'],
                    $startDateTime,
                    $endDateTime,
                    $eventID
                );
                if (!$venueConflictValidation['status']) {
                    return $venueConflictValidation;
                }

                $response = $this -> eventModel -> updateEvent(
                    $eventID,
                    $title,
                    $description,
                    $categoryID,
                    $venue['venue']['event_venue_id'],
                    $venue['venue']['event_venue_name'],
                    $venue['venue']['estimated_capacity'],
                    $startDateTime,
                    $endDateTime,
                    $statusID
                );

                if ($response === true) {
                    return [
                        "status" => true,
                        "message" => "Event updated successfully."
                    ];
                }

                if (is_array($response) && isset($response['status']) && $response['status'] === false) {
                    return $response;
                }

                return [
                    "status" => false,
                    "message" => "Failed to update event."
                ];

            } catch(Exception $e) {
                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(),
                    'Exception',
                    $e->getFile(),
                    $e->getLine(),
                    $_SESSION['user_id'] ?? null
                );

                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }

        public function deleteEvent($eventID, $userID, $roleID, $permissions) {
            try {
                if (!in_array('manage_events', $permissions)) {
                    return [
                        "status" => false,
                        "message" => "Unauthorized action."
                    ];
                }

                $event = $this -> eventModel -> searchEvent($eventID);
                if (!$event || (isset($event['status']) && $event['status'] === false)) {
                    return [
                        "status" => false,
                        "message" => "Event not found."
                    ];
                }

                if (!$this -> canManageEvent($event, $userID, $roleID, $permissions)) {
                    return [
                        "status" => false,
                        "message" => "You are not allowed to delete this event."
                    ];
                }

                $response = $this -> eventModel -> deleteEvent($eventID);

                if ($response === true) {
                    return [
                        "status" => true,
                        "message" => "Event deleted successfully."
                    ];
                }

                if (is_array($response) && isset($response['status']) && $response['status'] === false) {
                    return $response;
                }

                return [
                    "status" => false,
                    "message" => "Failed to delete event."
                ];

            } catch(Exception $e) {
                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(),
                    'Exception',
                    $e->getFile(),
                    $e->getLine(),
                    $_SESSION['user_id'] ?? null
                );

                http_response_code(400);
                echo $e -> getMessage();
                exit;
            }
        }

        private function canManageEvent($event, $userID, $roleID, $permissions) {
            if (!in_array('manage_events', $permissions)) {
                return false;
            }

            if ((int)$roleID === 1) {
                return true;
            }

            return (int)$event['created_by'] === (int)$userID;
        }

        private function validateEvent($title, $description, $categoryID, $location, $capacity, $startDateTime, $endDateTime, $statusID) {
            if (empty($title) || empty($description) || empty($categoryID) || empty($location) || empty($startDateTime) || empty($endDateTime) || empty($statusID)) {
                return [
                    "status" => false,
                    "message" => "Fill out all fields."
                ];
            }

            if (!is_numeric($statusID) || (int)$statusID <= 0) {
                return [
                    "status" => false,
                    "message" => "Invalid event status."
                ];
            }

            $start = strtotime($startDateTime);
            $end = strtotime($endDateTime);

            if (!$start || !$end) {
                return [
                    "status" => false,
                    "message" => "Invalid event date and time."
                ];
            }

            if ($end < $start) {
                return [
                    "status" => false,
                    "message" => "End date and time must be after the start."
                ];
            }

            if ($capacity !== '' && (!is_numeric($capacity) || (int)$capacity <= 0)) {
                return [
                    "status" => false,
                    "message" => "Capacity must be a positive number."
                ];
            }

            if (!is_numeric($categoryID) || (int)$categoryID <= 0) {
                return [
                    "status" => false,
                    "message" => "Invalid event category."
                ];
            }

            return [
                "status" => true
            ];
        }

        private function validateFutureStart($startDateTime) {
            $start = strtotime($startDateTime);
            $currentMinute = strtotime(date('Y-m-d H:i'));

            if (!$start || $start < $currentMinute) {
                return [
                    "status" => false,
                    "message" => "Start date and time cannot be before the current time."
                ];
            }

            return [
                "status" => true
            ];
        }

        private function validateCreateEvent($description) {
            if (strlen($description) > 300) {
                return [
                    "status" => false,
                    "message" => "Description must be 300 characters or fewer."
                ];
            }

            return [
                "status" => true
            ];
        }

        private function validateVenueAvailability($eventVenueID, $startDateTime, $endDateTime, $excludeEventID = null) {
            $hasConflict = $this -> eventModel -> hasVenueConflict(
                $eventVenueID,
                $startDateTime,
                $endDateTime,
                $excludeEventID
            );

            if (is_array($hasConflict) && isset($hasConflict['status']) && $hasConflict['status'] === false) {
                return $hasConflict;
            }

            if ($hasConflict) {
                return [
                    "status" => false,
                    "message" => "Selected venue is already reserved for the chosen time."
                ];
            }

            return [
                "status" => true
            ];
        }

        private function getValidatedVenue($eventVenueID) {
            if (!is_numeric($eventVenueID) || (int)$eventVenueID <= 0) {
                return [
                    "status" => false,
                    "message" => "Choose a valid venue."
                ];
            }

            $eventVenueManager = new EventVenueManager();
            $venue = $eventVenueManager -> getEventVenue((int)$eventVenueID);

            if (!$venue || (isset($venue['status']) && $venue['status'] === false)) {
                return [
                    "status" => false,
                    "message" => "Choose a valid venue."
                ];
            }

            return [
                "status" => true,
                "venue" => $venue
            ];
        }
    }
?>
