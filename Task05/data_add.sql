INSERT OR IGNORE INTO users (name, email, gender, register_date, occupation_id)
VALUES
('Моисеев Олег Максимович', 'moiseevom@gmail.com', 'male', date('now'),
 (SELECT id FROM occupations ORDER BY id LIMIT 1)),
('Непьянова Анна Павловна', 'nepianova@gmail.com', 'female', date('now'),
 (SELECT id FROM occupations ORDER BY id LIMIT 1)),
 ('Пазухина Анастасия Константиновна', 'pazuhina@gmail.com', 'female', date('now'),
 (SELECT id FROM occupations ORDER BY id LIMIT 1)),
('Пиганов Дмитрий Максимович', 'piganov@gmail.com', 'male', date('now'),
 (SELECT id FROM occupations ORDER BY id LIMIT 1)),
('Савин Руслан Вадимович', 'savin@gmail.com', 'male', date('now'),
 (SELECT id FROM occupations ORDER BY id LIMIT 1));



INSERT OR IGNORE INTO movies (title, year)
VALUES
('Оппенгеймер (2023)', 2023),
('Дюна (2021)', 2021),
('Бойцовский клуб (1999)', 1999);


INSERT OR IGNORE INTO genres (name) VALUES ('Sci-Fi');
INSERT OR IGNORE INTO genres (name) VALUES ('Drama');
INSERT OR IGNORE INTO genres (name) VALUES ('Action');
INSERT OR IGNORE INTO genres (name) VALUES ('Thriller');
INSERT OR IGNORE INTO genres (name) VALUES ('Adventure');

INSERT OR IGNORE INTO movie_genres (movie_id, genre_id)
SELECT m.id, g.id FROM movies m JOIN genres g ON g.name = 'Drama'
WHERE m.title = 'Оппенгеймер (2023)';

INSERT OR IGNORE INTO movie_genres (movie_id, genre_id)
SELECT m.id, g.id FROM movies m JOIN genres g ON g.name = 'Sci-Fi'
WHERE m.title = 'Дюна (2021)';

INSERT OR IGNORE INTO movie_genres (movie_id, genre_id)
SELECT m.id, g.id FROM movies m JOIN genres g ON g.name = 'Thriller'
WHERE m.title = 'Бойцовский клуб (1999)';


INSERT INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 5.0, strftime('%s','now')
FROM users u JOIN movies m ON m.title = 'Оппенгеймер (2023)'
WHERE u.email = 'pazuhina@gmail.com'
AND NOT EXISTS (
    SELECT 1 FROM ratings r WHERE r.user_id = u.id AND r.movie_id = m.id
);

INSERT INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.7, strftime('%s','now')
FROM users u JOIN movies m ON m.title = 'Дюна (2021)'
WHERE u.email = 'pazuhina@gmail.com'
AND NOT EXISTS (
    SELECT 1 FROM ratings r WHERE r.user_id = u.id AND r.movie_id = m.id
);

INSERT INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.9, strftime('%s','now')
FROM users u JOIN movies m ON m.title = 'Бойцовский клуб (1999)'
WHERE u.email = 'pazuhina@gmail.com'
AND NOT EXISTS (
    SELECT 1 FROM ratings r WHERE r.user_id = u.id AND r.movie_id = m.id
);