<?php
    require_once "../model/config/Database.php";
    require_once "../model/PermissionModel.php";
    require_once "ErrorLoggerManager.php";
    class PermissionManager {

        private $permissionManager;
        
        public function __construct() {
            $database = new Database();
            $db = $database -> connect();
            $this -> permissionManager = new PermissionModel($db);
        }
    
        public function getPermissions(){
            try {
                $response = $this -> permissionManager -> readPermissions();
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