<?php
    class EventStatusModel {
        private $conn;

        public function __construct($db) {
            $this -> conn = $db;
        }

        public function readEventStatuses() {
            $selectQuery = "SELECT *
                            FROM tbl_event_status
                            ORDER BY event_status_id ASC";
            $response = $this -> conn -> prepare($selectQuery);
            $response -> execute();

            return $response;
        }
    }
?>
