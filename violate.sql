/*Violates the unique id property of Movie table*/
INSERT INTO Movie VALUES (4663,"X",1996,"R","Shelty Company Limited");

/*Violates the unique id property of Actor table*/
INSERT INTO Actor VALUES (1,"A","Isabelle","Female",19750525,\N);

/*Violates the unique id property of Director table*/
INSERT INTO Director VALUES (37146,"Lipstadt","Aaron",19521112,\N);

/*Violates the foreign key constraint of director id in the MovieDirector table*/
INSERT INTO MovieDirector VALUES (4663, 69001);

/*Violates the foreign key constraint of movie id in the MovieDirector table*/
INSERT INTO MovieDirector VALUES (4751, 37146);

/*Violates the foreign key constraint of movie id in the MovieGenre table*/
INSERT INTO MovieGenre VALUES (4751, "Sci-Fi");

/*Violates the foreign key constraint of actor id in the MovieActor table*/
INSERT INTO MovieActor VALUES (4663, 69001, "Borat");

/*Violates the foreign key constraint of movie id in the MovieActor table*/
INSERT INTO MovieActor VALUES (4751, 37146, "Borat");

/*Violates the foreign key constraint of movie id in the MovieRating table*/
INSERT INTO MovieRating VALUES (4751, 80, 76);

/*Violates the foreign key constraint of movie id in the MovieReview table*/
INSERT INTO Review VALUES ('John Bigham', 1493051164 , 4751, 88, 'Excellent movie.');

/*Violates the range check constraint of IMDB rating in MovieRating table.*/
INSERT INTO MovieRating VALUES (4663, 101, 76);

/*Violates the range check constraint of Rotten Tomatoes rating in MovieRating table.*/
INSERT INTO MovieRating VALUES (4663, 99, 101);

/*Violates the range check constraint of rating in Review table.*/
INSERT INTO Review VALUES ('John Bigham', 1493051164 , 4663, 101, 'Excellent movie.');
