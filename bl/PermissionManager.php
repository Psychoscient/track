<?php
    require_once "../model/config/Database.php";
    require_once "../model/PermissionModel.php";
    class PermissionManager {

        private $permissionManager;
        
        public function __construct() {
            $database = new Database();
            $db = $database -> connect();
            $this -> permissionManager = new PermissionModel($db);
        }
    
        public function getPermissions(){
            $response = $this -> permissionManager -> readPermissions();
            return $response -> fetchAll(PDO::FETCH_ASSOC);
        }
    }

?>