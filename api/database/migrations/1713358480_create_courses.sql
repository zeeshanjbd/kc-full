-- down if exist
DROP TABLE IF EXISTS courses;

-- up
CREATE TABLE IF NOT EXISTS courses (
    id CHAR(36) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    preview VARCHAR(255),
    category_id CHAR(36) NOT NULL,
    main_category_id CHAR(36) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);
