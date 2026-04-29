<?php
    require_once "../model/config/Database.php";
    require_once "../model/ErrorLoggerModel.php";
    class ErrorLoggerManager {
        private $errorLoggerModel;
        
        public function __construct() {
            $database = new Database();
            $db = $database -> connect();
            $this -> errorLoggerModel = new ErrorLoggerModel($db);
        }
        
        public function logError($message, $type, $file, $line, $userID = null) {
            if($this -> errorLoggerModel -> logError($message, $type, $file, $line, $userID)) {
                return [
                    "status" => true,
                    "message" => "Logged successfully."
                ];
            }
        }
    }
?>