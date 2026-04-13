<?php
    class PermissionModel {
        private $conn;

        public function __construct($db) {
            $this -> conn = $db;
        }

        public function readPermissions() {
            $selectQuery = "SELECT * 
                            FROM tbl_permissions";
            $response = $this -> conn -> prepare($selectQuery);
            $response -> execute();

            return $response;
        }

    }
?>