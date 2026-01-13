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

-- ROLE
CREATE TABLE IF NOT EXISTS roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

INSERT INTO roles (name) VALUES 
('ADMIN'), ('MODERATOR'), ('USER')
ON CONFLICT DO NOTHING;

-- FIRMY
CREATE TABLE IF NOT EXISTS companies (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

INSERT INTO companies (name) VALUES
('Finch Studio'),
('MediaCorp'),
('VisionX');

-- UŻYTKOWNICY
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password TEXT,
    firstname VARCHAR(100),
    lastname VARCHAR(100),
    role_id INT REFERENCES roles(id),
    company_id INT REFERENCES companies(id),
    activation_code VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE
);

-- Hasło: admin123
INSERT INTO users (email, password, firstname, lastname, role_id, company_id, is_active)
VALUES (
    'admin@finch.pl',
    '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFQ5Rk5fYhK6m4G8M9p1U8G3s6C0KJ4a',
    'Finch',
    'Admin',
    (SELECT id FROM roles WHERE name='ADMIN'),
    1,
    true
);

-- Hasło: moderator123
INSERT INTO users (email, password, firstname, lastname, role_id, company_id, is_active)
VALUES (
    'moderator@finch.pl',
    '$2y$10$QeW4qK0V9Fh8KkFhGJ7Z3eJ0uU8c7UqgW6Uu8KZPq5U6xQnF3qKQ6',
    'Finch',
    'Moderator',
    (SELECT id FROM roles WHERE name='MODERATOR'),
    1,
    true
);

-- Hasło: user123
INSERT INTO users (email, password, firstname, lastname, role_id, company_id, is_active)
VALUES (
    'user@finch.pl',
    '$2y$10$ZsQ7Yy0QvH9rM9H6Q1Z8Xe4R7X6JpB8N9T1J0YyG5E0J3Q3OQO9wK',
    'John',
    'User',
    (SELECT id FROM roles WHERE name='USER'),
    2,
    true
);

-- ZLECENIA
CREATE TABLE IF NOT EXISTS assignments (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    video_path TEXT,
    company_id INT REFERENCES companies(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO assignments (title, description, video_path, company_id)
VALUES
('Promo Video', 'Create a promo video for Finch', 'public/uploads/sample1.mp4', 1),
('Ad Campaign', 'Short ad for MediaCorp', 'public/uploads/sample2.mp4', 2);

-- PRZYPISANIA ZLECEŃ
CREATE TABLE IF NOT EXISTS assignment_users (
    assignment_id INT REFERENCES assignments(id),
    user_id INT REFERENCES users(id)
);

INSERT INTO assignment_users VALUES (1, 3);
INSERT INTO assignment_users VALUES (2, 3);

-- KOMENTARZE
CREATE TABLE IF NOT EXISTS comments (
    id SERIAL PRIMARY KEY,
    assignment_id INT REFERENCES assignments(id),
    user_id INT REFERENCES users(id),
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO comments (assignment_id, user_id, content)
VALUES
(1, 3, 'Great footage, will start editing!'),
(1, 2, 'Make sure to follow brand guidelines.');
