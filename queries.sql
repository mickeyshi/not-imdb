 /*Find all actors in the movie 'Die Another Day'.*/
 SELECT CONCAT (a.first, ' ', a.last) 
 FROM Movie AS m, MovieActor AS ma, Actor AS a 
 WHERE m.title = 'Die Another Day' AND ma.mid = m.id AND ma.aid = a.id;

 /*All actors who worked in multiple movies.*/
 SELECT COUNT(DISTINCT ma1.aid)
 FROM MovieActor as ma1, MovieActor as ma2
 WHERE ma1.aid = ma2.aid AND ma1.mid <> ma2.mid;

 /*Titles of all movies that sold more than 1000000 tickets*/
 SELECT m.title
 FROM Movie AS m, Sales AS s
 WHERE s.ticketsSold > 1000000 AND s.mid = m.id;

 /*Title of movie that made the most money and title of movie that made the least money*/
 SELECT m.title
 FROM Movie as m, Sales as s
 WHERE s.totalIncome =
    (SELECT max(totalIncome) FROM Sales)
 AND s.mid = m.id
 UNION
 SELECT m.title
 FROM Movie as m, Sales as s
 WHERE s.totalIncome =
    (SELECT min(totalIncome) FROM Sales)
 AND s.mid = m.id;

 /*Directors of science fiction movies with a rating greater than 80 on IMDB*/
 SELECT CONCAT (d.first, ' ', d.last) 
 FROM Movie AS m, MovieDirector AS md, Director AS d, 
 MovieGenre as mg, MovieRating as mr
 WHERE 
    m.id = md.mid AND m.id = mg.mid AND m.id = mr.mid AND
    mg.genre = 'Sci-Fi' AND mr.imdb > 80 AND
    md.mid = m.id AND md.did = d.id;

