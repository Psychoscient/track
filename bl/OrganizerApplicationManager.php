<?php
    require_once "../model/config/Database.php";
    require_once "../model/OrganizerApplicationModel.php";
    require_once "UserManager.php";
    require_once "ErrorLoggerManager.php";

    class OrganizerApplicationManager {
        private $organizerApplicationModel;
        private $userManager;

        public function __construct() {
            $database = new Database();
            $db = $database -> connect();
            $this -> organizerApplicationModel = new OrganizerApplicationModel($db);
            $this -> userManager = new UserManager();
        }

        public function getLatestApplicationByUser($userID) {
            try {
                return $this -> organizerApplicationModel -> readLatestOrganizerApplicationByUser($userID);

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

        public function getPendingApplications() {
            try {
                $response = $this -> organizerApplicationModel -> readPendingOrganizerApplications();

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

        public function applyForOrganizer($userID, $roleID, $reason) {
            try {
                $user = $this -> userManager -> getUser("user_id", $userID);
                if (!$user || (isset($user['status']) && $user['status'] === false)) {
                    return [
                        "status" => false,
                        "message" => "User not found."
                    ];
                }

                if ((int)$user['role_id'] !== 2) {
                    return [
                        "status" => false,
                        "message" => "Only regular users can apply to become organizers."
                    ];
                }

                if (empty($reason)) {
                    return [
                        "status" => false,
                        "message" => "Please provide your reason for applying."
                    ];
                }

                $pendingApplication = $this -> organizerApplicationModel -> readPendingOrganizerApplicationByUser($userID);
                if ($pendingApplication && (!isset($pendingApplication['status']) || $pendingApplication['status'] !== false)) {
                    return [
                        "status" => false,
                        "message" => "You already have a pending organizer application."
                    ];
                }

                $response = $this -> organizerApplicationModel -> createOrganizerApplication($userID, $reason);

                if ($response === true) {
                    return [
                        "status" => true,
                        "message" => "Organizer application submitted successfully."
                    ];
                }

                if (is_array($response) && isset($response['status']) && $response['status'] === false) {
                    return $response;
                }

                return [
                    "status" => false,
                    "message" => "Failed to submit organizer application."
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

        public function approveApplication($applicationID, $reviewedBy, $permissions) {
            try {
                if (!in_array('manage_users', $permissions)) {
                    return [
                        "status" => false,
                        "message" => "Unauthorized action."
                    ];
                }

                $application = $this -> organizerApplicationModel -> searchOrganizerApplication($applicationID);
                if (!$application || (isset($application['status']) && $application['status'] === false)) {
                    return [
                        "status" => false,
                        "message" => "Application not found."
                    ];
                }

                if ($application['organizer_application_status_name'] !== 'pending') {
                    return [
                        "status" => false,
                        "message" => "Only pending applications can be approved."
                    ];
                }

                $applicationResponse = $this -> organizerApplicationModel -> updateOrganizerApplicationStatus($applicationID, 'approved', $reviewedBy);
                if (is_array($applicationResponse) && isset($applicationResponse['status']) && $applicationResponse['status'] === false) {
                    return $applicationResponse;
                }

                $roleResponse = $this -> userManager -> setUserRole($application['user_id'], 3);
                if (!$roleResponse['status']) {
                    return $roleResponse;
                }

                return [
                    "status" => true,
                    "message" => "Organizer application approved successfully."
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

        public function rejectApplication($applicationID, $reviewedBy, $permissions) {
            try {
                if (!in_array('manage_users', $permissions)) {
                    return [
                        "status" => false,
                        "message" => "Unauthorized action."
                    ];
                }

                $application = $this -> organizerApplicationModel -> searchOrganizerApplication($applicationID);
                if (!$application || (isset($application['status']) && $application['status'] === false)) {
                    return [
                        "status" => false,
                        "message" => "Application not found."
                    ];
                }

                if ($application['organizer_application_status_name'] !== 'pending') {
                    return [
                        "status" => false,
                        "message" => "Only pending applications can be rejected."
                    ];
                }

                $response = $this -> organizerApplicationModel -> updateOrganizerApplicationStatus($applicationID, 'rejected', $reviewedBy);

                if ($response === true) {
                    return [
                        "status" => true,
                        "message" => "Organizer application rejected successfully."
                    ];
                }

                if (is_array($response) && isset($response['status']) && $response['status'] === false) {
                    return $response;
                }

                return [
                    "status" => false,
                    "message" => "Failed to reject organizer application."
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
    }
?>
