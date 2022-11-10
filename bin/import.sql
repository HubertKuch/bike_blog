INSERT INTO news (id, title, description, tags, date)
SELECT uuid(), tytul, tresc, tagi, data
FROM newsy;

UPDATE news
SET tags = if(tags LIKE ';;%', SUBSTRING(tags, 3, 100), SUBSTRING(tags, 2, 100));
UPDATE news
SET tags = replace(tags, ';;', ';');
