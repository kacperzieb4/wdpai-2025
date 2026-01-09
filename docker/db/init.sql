-- DROP (dla czystego startu)
DROP TABLE IF EXISTS comments CASCADE;
DROP TABLE IF EXISTS assignment_users CASCADE;
DROP TABLE IF EXISTS assignments CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS roles CASCADE;

-- ROLES
CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

INSERT INTO roles (name) VALUES
('USER'),
('MODERATOR'),
('ADMIN');

-- USERS
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password TEXT NOT NULL,
    firstname VARCHAR(100),
    lastname VARCHAR(100),
    role_id INT REFERENCES roles(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ASSIGNMENTS
CREATE TABLE assignments (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    video_path TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- N:M RELATION
CREATE TABLE assignment_users (
    assignment_id INT REFERENCES assignments(id) ON DELETE CASCADE,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    PRIMARY KEY (assignment_id, user_id)
);

-- COMMENTS
CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    assignment_id INT REFERENCES assignments(id) ON DELETE CASCADE,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- VIEW 1
CREATE VIEW assignment_comment_count AS
SELECT 
    a.id,
    a.title,
    COUNT(c.id) AS comment_count
FROM assignments a
LEFT JOIN comments c ON a.id = c.assignment_id
GROUP BY a.id;

-- VIEW 2
CREATE VIEW user_assignments_count AS
SELECT 
    u.id,
    u.firstname,
    u.lastname,
    r.name AS role,
    COUNT(au.assignment_id) AS assignments
FROM users u
JOIN roles r ON u.role_id = r.id
LEFT JOIN assignment_users au ON u.id = au.user_id
GROUP BY u.id, r.name;

-- FUNCTION
CREATE OR REPLACE FUNCTION get_assignment_comments(a_id INT)
RETURNS TABLE(
    comment_id INT,
    content TEXT,
    created_at TIMESTAMP,
    user_name TEXT
)
AS $$
BEGIN
    RETURN QUERY
    SELECT 
        c.id,
        c.content,
        c.created_at,
        u.firstname || ' ' || u.lastname
    FROM comments c
    JOIN users u ON c.user_id = u.id
    WHERE c.assignment_id = a_id
    ORDER BY c.created_at;
END;
$$ LANGUAGE plpgsql;

-- TRIGGER FUNCTION
CREATE OR REPLACE FUNCTION update_assignment_timestamp()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE assignments
    SET updated_at = CURRENT_TIMESTAMP
    WHERE id = NEW.assignment_id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- TRIGGER
CREATE TRIGGER comment_update_trigger
AFTER INSERT ON comments
FOR EACH ROW
EXECUTE FUNCTION update_assignment_timestamp();
