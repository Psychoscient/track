<?php
    class RoleModel {
        private $conn;

        public function __construct($db) {
            $this -> conn = $db;
        }

        public function readRole() {
            $selectQuery = "SELECT * 
                            FROM tbl_roles";
            $response = $this -> conn -> prepare($selectQuery);
            $response -> execute();

            return $response;
        }

        public function readRolePermission() {
            $selectQuery = "SELECT
                                tbl_permissions.permission_id,
                                tbl_permissions.permission_name
                            FROM tbl_role_permissions
                            INNER JOIN tbl_permissions
                                ON tbl_role_permissions.permission_id = tbl_permissions.permission_id
                            WHERE
                                tbl_role_permissions.role_id = :role_id";
            $response = $this -> conn -> prepare($selectQuery);

            return $response;
        }

    }
?>