<?php
    require_once "../model/config/Database.php";
    require_once "../model/RoleModel.php";
    require_once "ErrorLoggerManager.php";
    class RoleManager {

        private $roleManager;
        
        public function __construct() {
            $database = new Database();
            $db = $database -> connect();
            $this -> roleManager = new RoleModel($db);
        }
    
        public function getRoles() {
            try {
                $response = $this -> roleManager -> readRole();
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

        public function getPermissions($roleID) {
            try {
                $response = $this -> roleManager -> readRolePermission();
                $response -> bindParam(':role_id', $roleID, PDO::PARAM_INT);
                $response -> execute();
                
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