CREATE TABLE IF NOT EXISTS tbl_event_venues (
    event_venue_id INT AUTO_INCREMENT PRIMARY KEY,
    event_venue_name VARCHAR(255) NOT NULL,
    event_venue_location VARCHAR(255) NOT NULL,
    estimated_capacity INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_event_venue_name (event_venue_name)
);

INSERT INTO tbl_event_venues (event_venue_name, event_venue_location, estimated_capacity)
SELECT 'Frassati Auditorium', 'St. Pier Giorgio Frassati, O.P. Building (Auditorium Level)', 1200
WHERE NOT EXISTS (SELECT 1 FROM tbl_event_venues WHERE event_venue_name = 'Frassati Auditorium');

INSERT INTO tbl_event_venues (event_venue_name, event_venue_location, estimated_capacity)
SELECT 'Dr. Robert C. Sy Grand Ballroom', 'Buenaventura Garcia Paredes, O.P. (BGPOP) Building', 450
WHERE NOT EXISTS (SELECT 1 FROM tbl_event_venues WHERE event_venue_name = 'Dr. Robert C. Sy Grand Ballroom');

INSERT INTO tbl_event_venues (event_venue_name, event_venue_location, estimated_capacity)
SELECT 'George S.K. Ty Function Halls', 'BGPOP Building (Rooms 401-404)', 250
WHERE NOT EXISTS (SELECT 1 FROM tbl_event_venues WHERE event_venue_name = 'George S.K. Ty Function Halls');

INSERT INTO tbl_event_venues (event_venue_name, event_venue_location, estimated_capacity)
SELECT '8/F Central Laboratory Auditorium', 'Central Laboratory Building (8th Floor)', 300
WHERE NOT EXISTS (SELECT 1 FROM tbl_event_venues WHERE event_venue_name = '8/F Central Laboratory Auditorium');

INSERT INTO tbl_event_venues (event_venue_name, event_venue_location, estimated_capacity)
SELECT 'TARC Auditorium', 'Thomas Aquinas Research Complex (Ground Floor)', 175
WHERE NOT EXISTS (SELECT 1 FROM tbl_event_venues WHERE event_venue_name = 'TARC Auditorium');

INSERT INTO tbl_event_venues (event_venue_name, event_venue_location, estimated_capacity)
SELECT 'UST Medicine Auditorium', 'St. Martin de Porres Building', 1200
WHERE NOT EXISTS (SELECT 1 FROM tbl_event_venues WHERE event_venue_name = 'UST Medicine Auditorium');

INSERT INTO tbl_event_venues (event_venue_name, event_venue_location, estimated_capacity)
SELECT 'Albertus Magnus Auditorium (Education Auditorium)', 'Albertus Magnus Building', 800
WHERE NOT EXISTS (SELECT 1 FROM tbl_event_venues WHERE event_venue_name = 'Albertus Magnus Auditorium (Education Auditorium)');

INSERT INTO tbl_event_venues (event_venue_name, event_venue_location, estimated_capacity)
SELECT 'Tan Yan Kee AVR', 'Tan Yan Kee Student Center (4th Floor)', 125
WHERE NOT EXISTS (SELECT 1 FROM tbl_event_venues WHERE event_venue_name = 'Tan Yan Kee AVR');

INSERT INTO tbl_event_venues (event_venue_name, event_venue_location, estimated_capacity)
SELECT 'UST Quadricentennial Pavilion (Main Arena)', 'UST Quadricentennial Pavilion', 5792
WHERE NOT EXISTS (SELECT 1 FROM tbl_event_venues WHERE event_venue_name = 'UST Quadricentennial Pavilion (Main Arena)');

INSERT INTO tbl_event_venues (event_venue_name, event_venue_location, estimated_capacity)
SELECT 'UST Grandstand and Open Field', 'UST Campus Open Grounds', 10000
WHERE NOT EXISTS (SELECT 1 FROM tbl_event_venues WHERE event_venue_name = 'UST Grandstand and Open Field');

INSERT INTO tbl_event_venues (event_venue_name, event_venue_location, estimated_capacity)
SELECT 'UST Plaza Mayor', 'Front of UST Main Building', 5000
WHERE NOT EXISTS (SELECT 1 FROM tbl_event_venues WHERE event_venue_name = 'UST Plaza Mayor');

INSERT INTO tbl_event_venues (event_venue_name, event_venue_location, estimated_capacity)
SELECT 'Hospital AVR / Multi-Purpose Hall', 'St. John Paul II Building (UST Hospital)', 175
WHERE NOT EXISTS (SELECT 1 FROM tbl_event_venues WHERE event_venue_name = 'Hospital AVR / Multi-Purpose Hall');

INSERT INTO tbl_event_venues (event_venue_name, event_venue_location, estimated_capacity)
SELECT 'Beato Angelico AV Room', 'Beato Angelico Building', 125
WHERE NOT EXISTS (SELECT 1 FROM tbl_event_venues WHERE event_venue_name = 'Beato Angelico AV Room');

INSERT INTO tbl_event_venues (event_venue_name, event_venue_location, estimated_capacity)
SELECT 'AB AV Room', 'St. Raymund de Penafort Building', 100
WHERE NOT EXISTS (SELECT 1 FROM tbl_event_venues WHERE event_venue_name = 'AB AV Room');

INSERT INTO tbl_event_venues (event_venue_name, event_venue_location, estimated_capacity)
SELECT 'Science AV Room', 'St. Albert the Great Building', 100
WHERE NOT EXISTS (SELECT 1 FROM tbl_event_venues WHERE event_venue_name = 'Science AV Room');

INSERT INTO tbl_event_venues (event_venue_name, event_venue_location, estimated_capacity)
SELECT 'Engineering Conference Room/AVR', 'Roque Ruano Building', 100
WHERE NOT EXISTS (SELECT 1 FROM tbl_event_venues WHERE event_venue_name = 'Engineering Conference Room/AVR');

INSERT INTO tbl_event_venues (event_venue_name, event_venue_location, estimated_capacity)
SELECT 'Main Gallery / UST Museum', 'UST Main Building', 125
WHERE NOT EXISTS (SELECT 1 FROM tbl_event_venues WHERE event_venue_name = 'Main Gallery / UST Museum');

ALTER TABLE tbl_events
ADD COLUMN event_venue_id INT NULL AFTER event_status_id;

UPDATE tbl_events
INNER JOIN tbl_event_venues
    ON tbl_events.location = tbl_event_venues.event_venue_name
SET tbl_events.event_venue_id = tbl_event_venues.event_venue_id
WHERE tbl_events.event_venue_id IS NULL;

ALTER TABLE tbl_events
ADD CONSTRAINT fk_events_venue
FOREIGN KEY (event_venue_id) REFERENCES tbl_event_venues (event_venue_id);

ALTER TABLE tbl_events
ADD INDEX idx_events_venue (event_venue_id);
