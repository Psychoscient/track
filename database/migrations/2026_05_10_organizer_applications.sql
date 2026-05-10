CREATE TABLE IF NOT EXISTS tbl_organizer_application_status (
    organizer_application_status_id INT AUTO_INCREMENT PRIMARY KEY,
    organizer_application_status_name VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO tbl_organizer_application_status (organizer_application_status_name)
SELECT 'pending'
WHERE NOT EXISTS (
    SELECT 1
    FROM tbl_organizer_application_status
    WHERE organizer_application_status_name = 'pending'
);

INSERT INTO tbl_organizer_application_status (organizer_application_status_name)
SELECT 'approved'
WHERE NOT EXISTS (
    SELECT 1
    FROM tbl_organizer_application_status
    WHERE organizer_application_status_name = 'approved'
);

INSERT INTO tbl_organizer_application_status (organizer_application_status_name)
SELECT 'rejected'
WHERE NOT EXISTS (
    SELECT 1
    FROM tbl_organizer_application_status
    WHERE organizer_application_status_name = 'rejected'
);

CREATE TABLE IF NOT EXISTS tbl_organizer_applications (
    organizer_application_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    reason TEXT NOT NULL,
    organizer_application_status_id INT NOT NULL,
    reviewed_by INT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_organizer_applications_user
        FOREIGN KEY (user_id) REFERENCES tbl_users (user_id),
    CONSTRAINT fk_organizer_applications_status
        FOREIGN KEY (organizer_application_status_id) REFERENCES tbl_organizer_application_status (organizer_application_status_id),
    CONSTRAINT fk_organizer_applications_reviewed_by
        FOREIGN KEY (reviewed_by) REFERENCES tbl_users (user_id),
    INDEX idx_organizer_applications_user (user_id),
    INDEX idx_organizer_applications_status (organizer_application_status_id),
    INDEX idx_organizer_applications_created_at (created_at)
);
