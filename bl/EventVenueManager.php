<?php
    require_once "../model/config/Database.php";
    require_once "../model/EventVenueModel.php";
    require_once "ErrorLoggerManager.php";

    class EventVenueManager {
        private $eventVenueModel;

        public function __construct() {
            $database = new Database();
            $db = $database -> connect();
            $this -> eventVenueModel = new EventVenueModel($db);
        }

        public function getEventVenues() {
            try {
                $response = $this -> eventVenueModel -> readEventVenues();
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

        public function getEventVenue($eventVenueID) {
            try {
                return $this -> eventVenueModel -> searchEventVenue($eventVenueID);

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
