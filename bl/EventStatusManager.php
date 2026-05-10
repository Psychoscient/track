<?php
    require_once "../model/config/Database.php";
    require_once "../model/EventStatusModel.php";
    require_once "ErrorLoggerManager.php";

    class EventStatusManager {
        private $eventStatusManager;

        public function __construct() {
            $database = new Database();
            $db = $database -> connect();
            $this -> eventStatusManager = new EventStatusModel($db);
        }

        public function getEventStatuses() {
            try {
                $response = $this -> eventStatusManager -> readEventStatuses();
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
    }
?>
