<?php
    class EventVenueModel {
        private $conn;

        public function __construct($db) {
            $this -> conn = $db;
        }

        public function readEventVenues() {
            $selectQuery = "SELECT *
                            FROM tbl_event_venues
                            ORDER BY event_venue_name ASC";
            $response = $this -> conn -> prepare($selectQuery);
            $response -> execute();

            return $response;
        }

        public function searchEventVenue($eventVenueID) {
            $selectQuery = "SELECT *
                            FROM tbl_event_venues
                            WHERE event_venue_id = :event_venue_id";
            $response = $this -> conn -> prepare($selectQuery);
            $response -> bindParam(':event_venue_id', $eventVenueID, PDO::PARAM_INT);
            $response -> execute();

            return $response -> fetch(PDO::FETCH_ASSOC);
        }
    }
?>
