<?php
    require_once "../model/config/Database.php";
    require_once "../model/EventCategoryModel.php";
    require_once "ErrorLoggerManager.php";

    class EventCategoryManager {
        private $eventCategoryManager;

        public function __construct() {
            $database = new Database();
            $db = $database -> connect();
            $this -> eventCategoryManager = new EventCategoryModel($db);
        }

        public function getEventCategories() {
            try {
                $response = $this -> eventCategoryManager -> readEventCategories();
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
