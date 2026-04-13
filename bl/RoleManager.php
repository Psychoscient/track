<?php
    require_once "../model/config/Database.php";
    require_once "../model/RoleModel.php";
    class RoleManager {

        private $roleManager;
        
        public function __construct() {
            $database = new Database();
            $db = $database -> connect();
            $this -> roleManager = new RoleModel($db);
        }
    
        public function getRoles() {
            $response = $this -> roleManager -> readRole();
            return $response -> fetchAll(PDO::FETCH_ASSOC);
        }

        public function getPermissions($roleID) {
            $response = $this -> roleManager -> readRolePermission();
            $response -> bindParam(':role_id', $roleID, PDO::PARAM_INT);
            $response -> execute();
            
            return $response -> fetchAll(PDO::FETCH_ASSOC);
        }
    }

?>