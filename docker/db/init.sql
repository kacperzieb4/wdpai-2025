CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    bio TEXT,
    enabled BOOLEAN DEFAULT TRUE
);

INSERT INTO users (firstname, lastname, email, password, bio, enabled)
VALUES (
    'Jan',
    'Kowalski',
    'jan.kowalski@example.com',
    '$2b$10$ZbzQrqD1vDhLJpYe/vzSbeDJHTUnVPCpwlXclkiFa8dO5gOAfg8tq',
    'Lubi programować w JS i PL/SQL.',
    TRUE
);
 
CREATE TABLE cards (
    id SERIAL PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL,
    image TEXT NOT NULL
);

INSERT INTO cards (title, description, image) VALUES
('Ace of Spades', 'Legendary card', 'https://deckofcardsapi.com/static/img/AS.png'),
('Queen of Hearts', 'Classic romance', 'https://deckofcardsapi.com/static/img/QH.png'),
('King of Clubs', 'Royal strength', 'https://deckofcardsapi.com/static/img/KC.png'),
('Jack of Diamonds', 'Sly and sharp', 'https://deckofcardsapi.com/static/img/JD.png'),
('Ten of Hearts', 'Lucky draw', 'https://deckofcardsapi.com/static/img/0H.png');
