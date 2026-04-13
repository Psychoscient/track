<?php
    require_once "../model/config/Database.php";
    require_once "../model/YearLevelModel.php";
    class YearLevelManager {

        private $yearlvlManager;
        
        public function __construct() {
            $database = new Database();
            $db = $database -> connect();
            $this -> yearlvlManager = new YearLevelModel($db);
        }
    
        public function getYearLevel(){
            $response = $this -> yearlvlManager -> readYearLevel();
            return $response -> fetchAll(PDO::FETCH_ASSOC);
        }
    }

?>