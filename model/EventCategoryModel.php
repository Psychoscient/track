<?php
    class EventCategoryModel {
        private $conn;

        public function __construct($db) {
            $this -> conn = $db;
        }

        public function readEventCategories() {
            $selectQuery = "SELECT *
                            FROM tbl_event_categories
                            ORDER BY event_category_name ASC";
            $response = $this -> conn -> prepare($selectQuery);
            $response -> execute();

            return $response;
        }
    }
?>
