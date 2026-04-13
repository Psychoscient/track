<?php
    class YearLevelModel {
        private $conn;

        public function __construct($db) {
            $this -> conn = $db;
        }

        public function readYearLevel() {
            $selectQuery = "SELECT * 
                            FROM tbl_year_lvl";
            $response = $this -> conn -> prepare($selectQuery);
            $response -> execute();

            return $response;
        }

    }
?>