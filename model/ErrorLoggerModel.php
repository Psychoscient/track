<?php
    class ErrorLoggerModel {
        private $conn;

        public function __construct($db) {
            $this -> conn = $db;
        }

        public function logError($message, $type, $file, $line, $userID = null) {
            $sessionID = session_id();

            $query = "INSERT INTO tbl_error_logs (error_message, error_type, file_path, line_number, user_id, session_id) 
                      VALUES (?, ?, ?, ?, ?, ?)";

            $response = $this -> conn -> prepare($query);
            $response->execute([$message, $type, $file, $line, $userID, $sessionID]);

            return $response;
        }

    }

?>