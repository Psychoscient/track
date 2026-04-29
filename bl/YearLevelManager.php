<?php
    require_once "../model/config/Database.php";
    require_once "../model/YearLevelModel.php";
    require_once "ErrorLoggerManager.php";
    class YearLevelManager {

        private $yearlvlManager;
        
        public function __construct() {
            $database = new Database();
            $db = $database -> connect();
            $this -> yearlvlManager = new YearLevelModel($db);
        }
    
        public function getYearLevel(){
            try {
                $response = $this -> yearlvlManager -> readYearLevel();
                return $response -> fetchAll(PDO::FETCH_ASSOC);
                
            } catch (Exception $e) {
                $errorLogger = new ErrorLoggerManager();
                $errorLogger -> logError(
                    $e->getMessage(), 
                    'Exception', 
                    $e->getFile(), 
                    $e->getLine(), 
                    $_SESSION['user_id'] ?? null
                );

                echo $e -> getMessage();
                exit;
            }
            
        }
    }

?>