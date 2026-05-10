    CREATE TABLE IF NOT EXISTS tbl_event_status (
        event_status_id INT AUTO_INCREMENT PRIMARY KEY,
        event_status_name VARCHAR(100) NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    INSERT INTO tbl_event_status (event_status_name)
    SELECT 'draft'
    WHERE NOT EXISTS (
        SELECT 1 FROM tbl_event_status WHERE event_status_name = 'draft'
    );

    INSERT INTO tbl_event_status (event_status_name)
    SELECT 'published'
    WHERE NOT EXISTS (
        SELECT 1 FROM tbl_event_status WHERE event_status_name = 'published'
    );

    INSERT INTO tbl_event_status (event_status_name)
    SELECT 'cancelled'
    WHERE NOT EXISTS (
        SELECT 1 FROM tbl_event_status WHERE event_status_name = 'cancelled'
    );

    ALTER TABLE tbl_events
    ADD COLUMN event_status_id INT NULL AFTER event_category_id;

    UPDATE tbl_events
    SET event_status_id = (
        SELECT event_status_id
        FROM tbl_event_status
        WHERE event_status_name = 'draft'
        LIMIT 1
    )
    WHERE status = 'draft';

    UPDATE tbl_events
    SET event_status_id = (
        SELECT event_status_id
        FROM tbl_event_status
        WHERE event_status_name = 'published'
        LIMIT 1
    )
    WHERE status = 'published';

    UPDATE tbl_events
    SET event_status_id = (
        SELECT event_status_id
        FROM tbl_event_status
        WHERE event_status_name = 'cancelled'
        LIMIT 1
    )
    WHERE status = 'cancelled';

    ALTER TABLE tbl_events
    MODIFY COLUMN event_status_id INT NOT NULL;

    ALTER TABLE tbl_events
    ADD CONSTRAINT fk_events_status
    FOREIGN KEY (event_status_id) REFERENCES tbl_event_status (event_status_id);

    ALTER TABLE tbl_events
    ADD INDEX idx_events_status (event_status_id);

    ALTER TABLE tbl_events
    DROP COLUMN status;
