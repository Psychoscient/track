<?php
    require_once "../bl/ErrorLoggerManager.php";

    class OrganizerApplicationModel {
        private $conn;

        public function __construct($db) {
            $this -> conn = $db;
        }

        public function readLatestOrganizerApplicationByUser($userID) {
            try {
                $selectQuery = "SELECT
                                    tbl_organizer_applications.organizer_application_id,
                                    tbl_organizer_applications.user_id,
                                    tbl_organizer_applications.reason,
                                    tbl_organizer_applications.organizer_application_status_id,
                                    tbl_organizer_applications.reviewed_by,
                                    tbl_organizer_applications.created_at,
                                    tbl_organizer_applications.updated_at,
                                    tbl_organizer_application_status.organizer_application_status_name
                                FROM tbl_organizer_applications
                                INNER JOIN tbl_organizer_application_status
                                    ON tbl_organizer_applications.organizer_application_status_id = tbl_organizer_application_status.organizer_application_status_id
                                WHERE tbl_organizer_applications.user_id = :user_id
                                ORDER BY tbl_organizer_applications.created_at DESC, tbl_organizer_applications.organizer_application_id DESC
                                LIMIT 1";

                $response = $this -> conn -> prepare($selectQuery);
                $response -> bindParam(':user_id', $userID, PDO::PARAM_INT);
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

        public function readPendingOrganizerApplicationByUser($userID) {
            try {
                $selectQuery = "SELECT
                                    tbl_organizer_applications.organizer_application_id,
                                    tbl_organizer_applications.user_id,
                                    tbl_organizer_applications.reason,
                                    tbl_organizer_applications.organizer_application_status_id,
                                    tbl_organizer_applications.reviewed_by,
                                    tbl_organizer_applications.created_at,
                                    tbl_organizer_applications.updated_at,
                                    tbl_organizer_application_status.organizer_application_status_name
                                FROM tbl_organizer_applications
                                INNER JOIN tbl_organizer_application_status
                                    ON tbl_organizer_applications.organizer_application_status_id = tbl_organizer_application_status.organizer_application_status_id
                                WHERE tbl_organizer_applications.user_id = :user_id
                                  AND tbl_organizer_application_status.organizer_application_status_name = 'pending'
                                ORDER BY tbl_organizer_applications.created_at DESC, tbl_organizer_applications.organizer_application_id DESC
                                LIMIT 1";

                $response = $this -> conn -> prepare($selectQuery);
                $response -> bindParam(':user_id', $userID, PDO::PARAM_INT);
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

        public function readPendingOrganizerApplications() {
            try {
                $selectQuery = "SELECT
                                    tbl_organizer_applications.organizer_application_id,
                                    tbl_organizer_applications.user_id,
                                    tbl_organizer_applications.reason,
                                    tbl_organizer_applications.organizer_application_status_id,
                                    tbl_organizer_applications.reviewed_by,
                                    tbl_organizer_applications.created_at,
                                    tbl_organizer_applications.updated_at,
                                    tbl_organizer_application_status.organizer_application_status_name,
                                    tbl_users.first_name,
                                    tbl_users.last_name,
                                    tbl_users.email,
                                    tbl_users.role_id,
                                    tbl_year_lvl.year_lvl_name
                                FROM tbl_organizer_applications
                                INNER JOIN tbl_organizer_application_status
                                    ON tbl_organizer_applications.organizer_application_status_id = tbl_organizer_application_status.organizer_application_status_id
                                INNER JOIN tbl_users
                                    ON tbl_organizer_applications.user_id = tbl_users.user_id
                                INNER JOIN tbl_year_lvl
                                    ON tbl_users.year_lvl_id = tbl_year_lvl.year_lvl_id
                                WHERE tbl_organizer_application_status.organizer_application_status_name = 'pending'
                                ORDER BY tbl_organizer_applications.created_at ASC, tbl_organizer_applications.organizer_application_id ASC";

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

        public function searchOrganizerApplication($applicationID) {
            try {
                $selectQuery = "SELECT
                                    tbl_organizer_applications.organizer_application_id,
                                    tbl_organizer_applications.user_id,
                                    tbl_organizer_applications.reason,
                                    tbl_organizer_applications.organizer_application_status_id,
                                    tbl_organizer_applications.reviewed_by,
                                    tbl_organizer_applications.created_at,
                                    tbl_organizer_applications.updated_at,
                                    tbl_organizer_application_status.organizer_application_status_name
                                FROM tbl_organizer_applications
                                INNER JOIN tbl_organizer_application_status
                                    ON tbl_organizer_applications.organizer_application_status_id = tbl_organizer_application_status.organizer_application_status_id
                                WHERE tbl_organizer_applications.organizer_application_id = :organizer_application_id";

                $response = $this -> conn -> prepare($selectQuery);
                $response -> bindParam(':organizer_application_id', $applicationID, PDO::PARAM_INT);
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

        public function createOrganizerApplication($userID, $reason) {
            try {
                $insertQuery = "INSERT INTO tbl_organizer_applications (
                                    user_id,
                                    reason,
                                    organizer_application_status_id,
                                    reviewed_by,
                                    created_at,
                                    updated_at
                                )
                                VALUES (
                                    :user_id,
                                    :reason,
                                    (
                                        SELECT organizer_application_status_id
                                        FROM tbl_organizer_application_status
                                        WHERE organizer_application_status_name = 'pending'
                                        LIMIT 1
                                    ),
                                    NULL,
                                    :created_at,
                                    :updated_at
                                )";

                $response = $this -> conn -> prepare($insertQuery);
                $dateNow = date('Y-m-d H:i:s');

                $response -> bindParam(':user_id', $userID, PDO::PARAM_INT);
                $response -> bindParam(':reason', $reason);
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

        public function updateOrganizerApplicationStatus($applicationID, $statusName, $reviewedBy) {
            try {
                $updateQuery = "UPDATE tbl_organizer_applications
                                SET
                                    organizer_application_status_id = (
                                        SELECT organizer_application_status_id
                                        FROM tbl_organizer_application_status
                                        WHERE organizer_application_status_name = :status_name
                                        LIMIT 1
                                    ),
                                    reviewed_by = :reviewed_by,
                                    updated_at = :updated_at
                                WHERE organizer_application_id = :organizer_application_id";

                $response = $this -> conn -> prepare($updateQuery);
                $dateNow = date('Y-m-d H:i:s');

                $response -> bindParam(':status_name', $statusName);
                $response -> bindParam(':reviewed_by', $reviewedBy, PDO::PARAM_INT);
                $response -> bindParam(':updated_at', $dateNow);
                $response -> bindParam(':organizer_application_id', $applicationID, PDO::PARAM_INT);

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
