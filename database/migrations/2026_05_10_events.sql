CREATE TABLE IF NOT EXISTS tbl_event_categories (
    event_category_id INT AUTO_INCREMENT PRIMARY KEY,
    event_category_name VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tbl_event_status (
    event_status_id INT AUTO_INCREMENT PRIMARY KEY,
    event_status_name VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO tbl_event_categories (event_category_name)
SELECT 'Seminar'
WHERE NOT EXISTS (
    SELECT 1 FROM tbl_event_categories WHERE event_category_name = 'Seminar'
);

INSERT INTO tbl_event_categories (event_category_name)
SELECT 'Workshop'
WHERE NOT EXISTS (
    SELECT 1 FROM tbl_event_categories WHERE event_category_name = 'Workshop'
);

INSERT INTO tbl_event_categories (event_category_name)
SELECT 'Meeting'
WHERE NOT EXISTS (
    SELECT 1 FROM tbl_event_categories WHERE event_category_name = 'Meeting'
);

INSERT INTO tbl_event_categories (event_category_name)
SELECT 'Competition'
WHERE NOT EXISTS (
    SELECT 1 FROM tbl_event_categories WHERE event_category_name = 'Competition'
);

INSERT INTO tbl_event_categories (event_category_name)
SELECT 'Organization Activity'
WHERE NOT EXISTS (
    SELECT 1 FROM tbl_event_categories WHERE event_category_name = 'Organization Activity'
);

INSERT INTO tbl_event_categories (event_category_name)
SELECT 'University Activity'
WHERE NOT EXISTS (
    SELECT 1 FROM tbl_event_categories WHERE event_category_name = 'University Activity'
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

CREATE TABLE IF NOT EXISTS tbl_events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    event_category_id INT NOT NULL,
    event_status_id INT NOT NULL,
    location VARCHAR(255) NOT NULL,
    capacity INT NULL,
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME NOT NULL,
    created_by INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_events_category
        FOREIGN KEY (event_category_id) REFERENCES tbl_event_categories (event_category_id),
    CONSTRAINT fk_events_status
        FOREIGN KEY (event_status_id) REFERENCES tbl_event_status (event_status_id),
    CONSTRAINT fk_events_user
        FOREIGN KEY (created_by) REFERENCES tbl_users (user_id),
    INDEX idx_events_status (event_status_id),
    INDEX idx_events_start_datetime (start_datetime),
    INDEX idx_events_created_by (created_by)
);

INSERT INTO tbl_permissions (permission_name)
SELECT 'manage_events'
WHERE NOT EXISTS (
    SELECT 1 FROM tbl_permissions WHERE permission_name = 'manage_events'
);

INSERT INTO tbl_role_permissions (role_id, permission_id)
SELECT 1, permission_id
FROM tbl_permissions
WHERE permission_name = 'manage_events'
AND NOT EXISTS (
    SELECT 1
    FROM tbl_role_permissions
    WHERE role_id = 1
    AND permission_id = (
        SELECT permission_id
        FROM tbl_permissions
        WHERE permission_name = 'manage_events'
        LIMIT 1
    )
);

INSERT INTO tbl_role_permissions (role_id, permission_id)
SELECT 3, permission_id
FROM tbl_permissions
WHERE permission_name = 'manage_events'
AND NOT EXISTS (
    SELECT 1
    FROM tbl_role_permissions
    WHERE role_id = 3
    AND permission_id = (
        SELECT permission_id
        FROM tbl_permissions
        WHERE permission_name = 'manage_events'
        LIMIT 1
    )
);
