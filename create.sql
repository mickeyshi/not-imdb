CREATE TABLE Movie(
    id int, 
    title varchar(100), 
    year int, 
    rating varchar(10), 
    company varchar(50),
    /*Movies have a unique movie-specific identification number.*/
    PRIMARY KEY (id)
    
) ENGINE=InnoDB;

CREATE TABLE Actor(
    id int, 
    last varchar(20), 
    first varchar(20), 
    sex varchar(6), 
    dob date, 
    dod date,
    /*Actors have a unique person-specific identification number.*/
    PRIMARY KEY (id)
)  ENGINE=InnoDB;

CREATE TABLE Sales(
    mid int, 
    ticketsSold int, 
    totalIncome int
)  ENGINE=InnoDB;

/*CREATE TABLE Director(id int, last varchar(20), first varchar(20), dob date, dod date, FOREIGN KEY (id) REFERENCES Actor(id));*/
CREATE TABLE Director(
    id int, 
    last varchar(20), 
    first varchar(20), 
    dob date, 
    dod date,
    /*Directors have a unique person-specific identification number.*/
    PRIMARY KEY (id)
)  ENGINE=InnoDB;

CREATE TABLE MovieGenre(
    mid int, 
    genre varchar(20),
    /*Every movie genre classification must have a valid movie ID in the Movie table.*/
    FOREIGN KEY (mid) REFERENCES Movie(id)
)  ENGINE=InnoDB;

CREATE TABLE MovieDirector(
    mid int, 
    did int,
    /*Every movie director classification must have a valid movie ID in the Movie table.*/
    FOREIGN KEY (mid) REFERENCES Movie(id),
    /*Every movie director classification must have a valid person ID in the Director table.*/
    FOREIGN KEY (did) REFERENCES Director(id)
) ENGINE=InnoDB;

CREATE TABLE MovieActor(
    mid int, 
    aid int, 
    role varchar(50),
    /*Every movie actor classification must have a valid movie ID in the Movie table.*/
    FOREIGN KEY (mid) REFERENCES Movie(id),
    /*Every movie actor classification must have a valid person ID in the Actor table.*/
    FOREIGN KEY (aid) REFERENCES Actor(id)
) ENGINE=InnoDB;

CREATE TABLE MovieRating(
    mid int, 
    imdb int, 
    rot int,
    /*Every movie rating must have a valid movie ID in the Movie table.*/
    FOREIGN KEY (mid) REFERENCES Movie(id),
    /*IMDB rating must be between 0 and 100.*/
    CHECK(imdb >= 0 AND imdb <= 100),
    /*Rotten Tomatoes rating must be between 0 and 100.*/
    CHECK(rot >= 0 AND rot <= 100)
) ENGINE=InnoDB;

CREATE TABLE Review(
    name varchar(20), 
    time timestamp, 
    mid int, 
    rating int, 
    comment varchar(500),
    /*Every movie rating must have a valid movie ID in the Movie table.*/
    FOREIGN KEY (mid) REFERENCES Movie(id),
    CHECK(rating >= 0 AND rating <= 100)
    ) ENGINE=InnoDB;

CREATE TABLE MaxPersonID(
    id int
    ) ENGINE=InnoDB;
/*
CREATE TRIGGER IncrementMaxPerson AFTER INSERT ON Actor
BEGIN
    UPDATE MaxPersonID SET id = id + 1;
END;

CREATE TRIGGER IncrementMaxPerson2 AFTER INSERT ON Director
BEGIN
    UPDATE MaxPersonID SET id = id + 1;
END;*/

CREATE TABLE MaxMovieID(
    id int
    ) ENGINE=InnoDB;
/*
CREATE TRIGGER IncrementMaxMovie AFTER INSERT ON Movie
BEGIN
    UPDATE MaxMovieID SET id = id + 1;
END;*/