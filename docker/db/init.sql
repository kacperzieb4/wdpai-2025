-- =========================
-- CLEAN START
-- =========================
DROP TABLE IF EXISTS comments CASCADE;
DROP TABLE IF EXISTS assignments CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS companies CASCADE;
DROP TABLE IF EXISTS roles CASCADE;

-- =========================
-- ROLES
-- =========================
CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

INSERT INTO roles (name) VALUES
('ADMIN'),
('MODERATOR'),
('USER');

-- =========================
-- COMPANIES
-- =========================
CREATE TABLE companies (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL,
    is_protected BOOLEAN DEFAULT FALSE
);

INSERT INTO companies (name, is_protected) VALUES
('Finch Studio', TRUE),
('MediaCorp', FALSE),
('VisionX', FALSE);

-- =========================
-- USERS
-- =========================
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password TEXT,
    firstname VARCHAR(100),
    lastname VARCHAR(100),
    role_id INT NOT NULL REFERENCES roles(id),
    company_id INT REFERENCES companies(id) ON DELETE SET NULL,
    activation_code VARCHAR(100),
    is_active BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- hasło: admin123 / moderator123 / user123
INSERT INTO users (email, password, firstname, lastname, role_id, company_id, is_active)
VALUES
(
    'admin@finch.pl',
    '$2a$12$fB2uG.TlFNStF8oc1ergQuZr/IU1XDOWkgMbYb21DprkRULce31my',
    'Finch',
    'Admin',
    (SELECT id FROM roles WHERE name = 'ADMIN'),
    (SELECT id FROM companies WHERE name = 'Finch Studio'),
    TRUE
),
(
    'moderator@finch.pl',
    '$2a$12$fB2uG.TlFNStF8oc1ergQuZr/IU1XDOWkgMbYb21DprkRULce31my',
    'Finch',
    'Moderator',
    (SELECT id FROM roles WHERE name = 'MODERATOR'),
    (SELECT id FROM companies WHERE name = 'Finch Studio'),
    TRUE
),
(
    'user@finch.pl',
    '$2a$12$fB2uG.TlFNStF8oc1ergQuZr/IU1XDOWkgMbYb21DprkRULce31my',
    'John',
    'User',
    (SELECT id FROM roles WHERE name = 'USER'),
    (SELECT id FROM companies WHERE name = 'MediaCorp'),
    TRUE
);

-- =========================
-- ASSIGNMENTS
-- =========================
CREATE TABLE assignments (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    video_path TEXT NOT NULL,
    company_id INT NOT NULL REFERENCES companies(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO assignments (title, description, video_path, company_id)
VALUES
(
    'Promo Video',
    'Create a promo video for Finch',
    'public/uploads/sample1.mp4',
    (SELECT id FROM companies WHERE name = 'Finch Studio')
),
(
    'Ad Campaign',
    'Short ad for MediaCorp',
    'public/uploads/sample2.mp4',
    (SELECT id FROM companies WHERE name = 'MediaCorp')
),
(
    'VisionX Teaser',
    'Product teaser video',
    'public/uploads/sample3.mp4',
    (SELECT id FROM companies WHERE name = 'VisionX')
);

-- =========================
-- COMMENTS
-- =========================
CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    assignment_id INT NOT NULL REFERENCES assignments(id) ON DELETE CASCADE,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    content TEXT NOT NULL,
    video_timestamp INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO comments (assignment_id, user_id, content, video_timestamp)
VALUES
(
    (SELECT id FROM assignments WHERE title = 'Promo Video'),
    (SELECT id FROM users WHERE email = 'user@finch.pl'),
    'Great footage, will start editing!',
    12
),
(
    (SELECT id FROM assignments WHERE title = 'Promo Video'),
    (SELECT id FROM users WHERE email = 'moderator@finch.pl'),
    'Make sure to follow brand guidelines.',
    NULL
);

-- =========================
-- VIEWS
-- =========================
CREATE VIEW assignment_comment_count AS
SELECT 
    a.id,
    a.title,
    COUNT(c.id) AS comment_count
FROM assignments a
LEFT JOIN comments c ON a.id = c.assignment_id
GROUP BY a.id;

CREATE VIEW user_assignment_count AS
SELECT
    u.id AS user_id,
    u.email,
    COUNT(a.id) AS assignments_count
FROM users u
LEFT JOIN assignments a ON a.company_id = u.company_id
GROUP BY u.id, u.email;

-- =========================
-- FUNCTION
-- =========================
CREATE OR REPLACE FUNCTION get_assignment_comments(a_id INT)
RETURNS TABLE(
    comment_id INT,
    content TEXT,
    created_at TIMESTAMP,
    video_timestamp INT,
    user_name TEXT
)
AS $$
BEGIN
    RETURN QUERY
    SELECT 
        c.id,
        c.content,
        c.created_at,
        c.video_timestamp,
        u.firstname || ' ' || u.lastname
    FROM comments c
    JOIN users u ON c.user_id = u.id
    WHERE c.assignment_id = a_id
    ORDER BY c.created_at DESC;
END;
$$ LANGUAGE plpgsql;

-- =========================
-- TRIGGER
-- =========================
CREATE OR REPLACE FUNCTION update_assignment_timestamp()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE assignments
    SET updated_at = CURRENT_TIMESTAMP
    WHERE id = NEW.assignment_id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER comment_update_trigger
AFTER INSERT ON comments
FOR EACH ROW
EXECUTE FUNCTION update_assignment_timestamp();
