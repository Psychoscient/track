<?php
    require_once "../bl/ErrorLoggerManager.php";

    class EventModel {
        private $conn;

        public function __construct($db) {
            $this -> conn = $db;
        }

        public function readPublishedEvents() {
            try {
                $selectQuery = "SELECT
                                    tbl_events.event_id,
                                    tbl_events.title,
                                    tbl_events.description,
                                    tbl_events.event_category_id,
                                    tbl_events.event_status_id,
                                    tbl_events.event_venue_id,
                                    tbl_events.location,
                                    tbl_events.capacity,
                                    tbl_events.start_datetime,
                                    tbl_events.end_datetime,
                                    tbl_events.created_by,
                                    tbl_events.created_at,
                                    tbl_events.updated_at,
                                    tbl_event_categories.event_category_name,
                                    tbl_event_status.event_status_name,
                                    tbl_event_venues.event_venue_name,
                                    tbl_event_venues.event_venue_location,
                                    tbl_event_venues.estimated_capacity,
                                    tbl_users.first_name,
                                    tbl_users.last_name
                                FROM tbl_events
                                INNER JOIN tbl_event_categories
                                    ON tbl_events.event_category_id = tbl_event_categories.event_category_id
                                INNER JOIN tbl_event_status
                                    ON tbl_events.event_status_id = tbl_event_status.event_status_id
                                LEFT JOIN tbl_event_venues
                                    ON tbl_events.event_venue_id = tbl_event_venues.event_venue_id
                                INNER JOIN tbl_users
                                    ON tbl_events.created_by = tbl_users.user_id
                                WHERE tbl_event_status.event_status_name = 'published'
                                ORDER BY tbl_events.start_datetime ASC, tbl_events.created_at DESC";

                $response = $this -> conn -> prepare($selectQuery);
                $response -> execute();

                return $response;

            } catch(Exception $e) {
                http_response_code(400);

                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(),
                    'Exception',
                    $e->getFile(),
                    $e->getLine(),
                    $_SESSION['user_id'] ?? null
                );

                return [
                    "status" => false,
                    "message" => "Database error: " . $e -> getMessage()
                ];
            }
        }

        public function readAllEvents() {
            try {
                $selectQuery = "SELECT
                                    tbl_events.event_id,
                                    tbl_events.title,
                                    tbl_events.description,
                                    tbl_events.event_category_id,
                                    tbl_events.event_status_id,
                                    tbl_events.event_venue_id,
                                    tbl_events.location,
                                    tbl_events.capacity,
                                    tbl_events.start_datetime,
                                    tbl_events.end_datetime,
                                    tbl_events.created_by,
                                    tbl_events.created_at,
                                    tbl_events.updated_at,
                                    tbl_event_categories.event_category_name,
                                    tbl_event_status.event_status_name,
                                    tbl_event_venues.event_venue_name,
                                    tbl_event_venues.event_venue_location,
                                    tbl_event_venues.estimated_capacity,
                                    tbl_users.first_name,
                                    tbl_users.last_name
                                FROM tbl_events
                                INNER JOIN tbl_event_categories
                                    ON tbl_events.event_category_id = tbl_event_categories.event_category_id
                                INNER JOIN tbl_event_status
                                    ON tbl_events.event_status_id = tbl_event_status.event_status_id
                                LEFT JOIN tbl_event_venues
                                    ON tbl_events.event_venue_id = tbl_event_venues.event_venue_id
                                INNER JOIN tbl_users
                                    ON tbl_events.created_by = tbl_users.user_id
                                ORDER BY tbl_events.start_datetime ASC, tbl_events.created_at DESC";

                $response = $this -> conn -> prepare($selectQuery);
                $response -> execute();

                return $response;

            } catch(Exception $e) {
                http_response_code(400);

                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(),
                    'Exception',
                    $e->getFile(),
                    $e->getLine(),
                    $_SESSION['user_id'] ?? null
                );

                return [
                    "status" => false,
                    "message" => "Database error: " . $e -> getMessage()
                ];
            }
        }

        public function readOrganizerEvents($userID) {
            try {
                $selectQuery = "SELECT
                                    tbl_events.event_id,
                                    tbl_events.title,
                                    tbl_events.description,
                                    tbl_events.event_category_id,
                                    tbl_events.event_status_id,
                                    tbl_events.event_venue_id,
                                    tbl_events.location,
                                    tbl_events.capacity,
                                    tbl_events.start_datetime,
                                    tbl_events.end_datetime,
                                    tbl_events.created_by,
                                    tbl_events.created_at,
                                    tbl_events.updated_at,
                                    tbl_event_categories.event_category_name,
                                    tbl_event_status.event_status_name,
                                    tbl_event_venues.event_venue_name,
                                    tbl_event_venues.event_venue_location,
                                    tbl_event_venues.estimated_capacity,
                                    tbl_users.first_name,
                                    tbl_users.last_name
                                FROM tbl_events
                                INNER JOIN tbl_event_categories
                                    ON tbl_events.event_category_id = tbl_event_categories.event_category_id
                                INNER JOIN tbl_event_status
                                    ON tbl_events.event_status_id = tbl_event_status.event_status_id
                                LEFT JOIN tbl_event_venues
                                    ON tbl_events.event_venue_id = tbl_event_venues.event_venue_id
                                INNER JOIN tbl_users
                                    ON tbl_events.created_by = tbl_users.user_id
                                WHERE tbl_event_status.event_status_name = 'published'
                                   OR tbl_events.created_by = :user_id
                                ORDER BY tbl_events.start_datetime ASC, tbl_events.created_at DESC";

                $response = $this -> conn -> prepare($selectQuery);
                $response -> bindParam(':user_id', $userID, PDO::PARAM_INT);
                $response -> execute();

                return $response;

            } catch(Exception $e) {
                http_response_code(400);

                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(),
                    'Exception',
                    $e->getFile(),
                    $e->getLine(),
                    $_SESSION['user_id'] ?? null
                );

                return [
                    "status" => false,
                    "message" => "Database error: " . $e -> getMessage()
                ];
            }
        }

        public function searchEvent($eventID) {
            try {
                $selectQuery = "SELECT
                                    tbl_events.event_id,
                                    tbl_events.title,
                                    tbl_events.description,
                                    tbl_events.event_category_id,
                                    tbl_events.event_status_id,
                                    tbl_events.event_venue_id,
                                    tbl_events.location,
                                    tbl_events.capacity,
                                    tbl_events.start_datetime,
                                    tbl_events.end_datetime,
                                    tbl_events.created_by,
                                    tbl_events.created_at,
                                    tbl_events.updated_at,
                                    tbl_event_categories.event_category_name,
                                    tbl_event_status.event_status_name,
                                    tbl_event_venues.event_venue_name,
                                    tbl_event_venues.event_venue_location,
                                    tbl_event_venues.estimated_capacity,
                                    tbl_users.first_name,
                                    tbl_users.last_name
                                FROM tbl_events
                                INNER JOIN tbl_event_categories
                                    ON tbl_events.event_category_id = tbl_event_categories.event_category_id
                                INNER JOIN tbl_event_status
                                    ON tbl_events.event_status_id = tbl_event_status.event_status_id
                                LEFT JOIN tbl_event_venues
                                    ON tbl_events.event_venue_id = tbl_event_venues.event_venue_id
                                INNER JOIN tbl_users
                                    ON tbl_events.created_by = tbl_users.user_id
                                WHERE tbl_events.event_id = :event_id";

                $response = $this -> conn -> prepare($selectQuery);
                $response -> bindParam(':event_id', $eventID, PDO::PARAM_INT);
                $response -> execute();

                return $response -> fetch(PDO::FETCH_ASSOC);

            } catch(Exception $e) {
                http_response_code(400);

                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(),
                    'Exception',
                    $e->getFile(),
                    $e->getLine(),
                    $_SESSION['user_id'] ?? null
                );

                return [
                    "status" => false,
                    "message" => "Database error: " . $e -> getMessage()
                ];
            }
        }

        public function hasVenueConflict($eventVenueID, $startDateTime, $endDateTime, $excludeEventID = null) {
            try {
                $selectQuery = "SELECT 1
                                FROM tbl_events
                                INNER JOIN tbl_event_status
                                    ON tbl_events.event_status_id = tbl_event_status.event_status_id
                                WHERE tbl_events.event_venue_id = :event_venue_id
                                  AND tbl_event_status.event_status_name IN ('draft', 'published')
                                  AND tbl_events.start_datetime < :end_datetime
                                  AND tbl_events.end_datetime > :start_datetime";

                if ($excludeEventID !== null) {
                    $selectQuery .= " AND tbl_events.event_id <> :exclude_event_id";
                }

                $selectQuery .= " LIMIT 1";

                $response = $this -> conn -> prepare($selectQuery);
                $response -> bindParam(':event_venue_id', $eventVenueID, PDO::PARAM_INT);
                $response -> bindParam(':start_datetime', $startDateTime);
                $response -> bindParam(':end_datetime', $endDateTime);

                if ($excludeEventID !== null) {
                    $response -> bindParam(':exclude_event_id', $excludeEventID, PDO::PARAM_INT);
                }

                $response -> execute();

                return $response -> fetch(PDO::FETCH_ASSOC) !== false;

            } catch(Exception $e) {
                http_response_code(400);

                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(),
                    'Exception',
                    $e->getFile(),
                    $e->getLine(),
                    $_SESSION['user_id'] ?? null
                );

                return [
                    "status" => false,
                    "message" => "Database error: " . $e -> getMessage()
                ];
            }
        }

        public function createEvent($title, $description, $categoryID, $eventVenueID, $location, $capacity, $startDateTime, $endDateTime, $statusID, $createdBy) {
            try {
                $insertQuery = "INSERT INTO tbl_events (
                                    title,
                                    description,
                                    event_category_id,
                                    event_status_id,
                                    event_venue_id,
                                    location,
                                    capacity,
                                    start_datetime,
                                    end_datetime,
                                    created_by,
                                    created_at,
                                    updated_at
                                )
                                VALUES (
                                    :title,
                                    :description,
                                    :event_category_id,
                                    :event_status_id,
                                    :event_venue_id,
                                    :location,
                                    :capacity,
                                    :start_datetime,
                                    :end_datetime,
                                    :created_by,
                                    :created_at,
                                    :updated_at
                                )";

                $response = $this -> conn -> prepare($insertQuery);
                $dateNow = date('Y-m-d H:i:s');
                $eventCapacity = $capacity === '' ? null : $capacity;

                $response -> bindParam(':title', $title);
                $response -> bindParam(':description', $description);
                $response -> bindParam(':event_category_id', $categoryID, PDO::PARAM_INT);
                $response -> bindParam(':event_status_id', $statusID, PDO::PARAM_INT);
                $response -> bindParam(':event_venue_id', $eventVenueID, PDO::PARAM_INT);
                $response -> bindParam(':location', $location);
                $response -> bindParam(':capacity', $eventCapacity, $eventCapacity === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
                $response -> bindParam(':start_datetime', $startDateTime);
                $response -> bindParam(':end_datetime', $endDateTime);
                $response -> bindParam(':created_by', $createdBy, PDO::PARAM_INT);
                $response -> bindParam(':created_at', $dateNow);
                $response -> bindParam(':updated_at', $dateNow);

                return $response -> execute();

            } catch(Exception $e) {
                http_response_code(400);

                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(),
                    'Exception',
                    $e->getFile(),
                    $e->getLine(),
                    $_SESSION['user_id'] ?? null
                );

                return [
                    "status" => false,
                    "message" => "Database error: " . $e -> getMessage()
                ];
            }
        }

        public function updateEvent($eventID, $title, $description, $categoryID, $eventVenueID, $location, $capacity, $startDateTime, $endDateTime, $statusID) {
            try {
                $updateQuery = "UPDATE tbl_events
                                SET
                                    title = :title,
                                    description = :description,
                                    event_category_id = :event_category_id,
                                    event_status_id = :event_status_id,
                                    event_venue_id = :event_venue_id,
                                    location = :location,
                                    capacity = :capacity,
                                    start_datetime = :start_datetime,
                                    end_datetime = :end_datetime,
                                    updated_at = :updated_at
                                WHERE event_id = :event_id";

                $response = $this -> conn -> prepare($updateQuery);
                $dateNow = date('Y-m-d H:i:s');
                $eventCapacity = $capacity === '' ? null : $capacity;

                $response -> bindParam(':title', $title);
                $response -> bindParam(':description', $description);
                $response -> bindParam(':event_category_id', $categoryID, PDO::PARAM_INT);
                $response -> bindParam(':event_status_id', $statusID, PDO::PARAM_INT);
                $response -> bindParam(':event_venue_id', $eventVenueID, PDO::PARAM_INT);
                $response -> bindParam(':location', $location);
                $response -> bindParam(':capacity', $eventCapacity, $eventCapacity === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
                $response -> bindParam(':start_datetime', $startDateTime);
                $response -> bindParam(':end_datetime', $endDateTime);
                $response -> bindParam(':updated_at', $dateNow);
                $response -> bindParam(':event_id', $eventID, PDO::PARAM_INT);

                return $response -> execute();

            } catch(Exception $e) {
                http_response_code(400);

                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(),
                    'Exception',
                    $e->getFile(),
                    $e->getLine(),
                    $_SESSION['user_id'] ?? null
                );

                return [
                    "status" => false,
                    "message" => "Database error: " . $e -> getMessage()
                ];
            }
        }

        public function deleteEvent($eventID) {
            try {
                $deleteQuery = "DELETE FROM tbl_events
                                WHERE event_id = :event_id";
                $response = $this -> conn -> prepare($deleteQuery);
                $response -> bindParam(':event_id', $eventID, PDO::PARAM_INT);

                return $response -> execute();

            } catch(Exception $e) {
                http_response_code(400);

                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(),
                    'Exception',
                    $e->getFile(),
                    $e->getLine(),
                    $_SESSION['user_id'] ?? null
                );

                return [
                    "status" => false,
                    "message" => "Database error: " . $e -> getMessage()
                ];
            }
        }
    }
?>
