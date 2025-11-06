-- Test SQL file for restore function testing
-- This is a simple test file to verify the restore functionality

CREATE TABLE IF NOT EXISTS test_restore_table (
    id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

INSERT INTO test_restore_table (name) VALUES 
('Test Record 1'),
('Test Record 2'),
('Test Record 3');

-- This file contains valid SQL statements for testing
-- It should be accepted by the restore validation