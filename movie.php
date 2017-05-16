<html>
<head>
    <title>!mdb</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="./styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Archivo+Narrow|Cabin+Condensed|Khand" rel="stylesheet"> 
</head>
<body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="./">!MDB</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">ADD <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="./addperson.php">Actor/Director</a></li>
            <li><a href="./addmovie.php">Movie</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="./actortomovie.php">Actor to Movie</a></li>
            <li><a href="./dirtomovie.php">Director to Movie</a></li>
          </ul>
        </li>
      </ul>
      <form class="navbar-form navbar-left" METHOD = "GET" ACTION = "./search.php">
        <div class="form-group">
          <input type="text" class="form-control" NAME = "query" placeholder="Movie title/actor name" >
        </div>
        <button type="submit" class="btn btn-default" >Search</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="./random.php">Random page</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<?php if($_GET["mid"]){

    //CLASS DECLARATIONS----------------------------------------------------
    class Actor{
        public $id;
        public $last;
        public $first;
        public $sex;
        public $dob;
        public $dod;
    }
    class MovieActor{
        public $mid;
        public $aid;
        public $role;
    }
    class Director{
        public $id;
        public $first;
        public $last;
    }
    class Movie{
        public $id; 
        public $title;
        public $year; 
        public $rating; 
        public $company;
    }
    class Casting{
        public $aid;
        public $last;
        public $first;
        public $role;
    }
    class Genre{
        public $genre;
    }
    class Rating{
        public $imdb;
        public $rating;
    }
    class Review{
        public $name;
        public $time;
        public $rating;
        public $comment;
    }
    $movie = new Movie;
    $cast = array();
    $castindex = 0;
    $directors = array();
    $directorindex = 0;
    $genres = array();
    $genreindex = 0;
    $ratings = array();
    $ratingindex = 0;
    $reviews = array();
    $reviewindex = 0;
    //MYSQL INITIALIZING CONNECTION-----------------------------------------
    $db_connection = mysql_connect("localhost", "cs143", "");
    if(!$db_connection) {
        $errmsg = mysql_error($db_connection);
        echo "Connection failed:" . $errmsg . "<br />";
        exit(1);
    }

    mysql_select_db("CS143", $db_connection);

    //MYSQL SENDING COMMENT-------------------------------------------------
    if($_GET["mid"] && $_GET["rating"] && $_GET["cmname"] && $_GET["comment"] && $_GET["date"]) {
        $insert = "INSERT INTO Review VALUES (\"".$_GET["cmname"]."\", \"".$_GET["date"]."\", ".$_GET["mid"].", ".$_GET["rating"].", \"".$_GET["comment"]."\");";
        //echo $insert;
        $rs = mysql_query($insert, $db_connection);
        if($rs){
            echo '<div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                Your comment was successfully submitted. 
            </div>';
        }
        
    }
    //MYSQL FETCHING--------------------------------------------------------

    $mid = $_GET["mid"];
    //$query = mysql_real_escape_string($_POST["query"], $db_connection);
    //echo $mid;
    echo "<br>";

    $rs = mysql_query("SELECT * FROM Movie WHERE id = $mid", $db_connection);
    $movie = mysql_fetch_object($rs, 'Movie');
    
    $rs = mysql_query("SELECT aid, last, first, role FROM Actor, MovieActor WHERE Actor.id = MovieActor.aid AND MovieActor.mid = $mid;", $db_connection);
    while($cast[$castindex] = mysql_fetch_object($rs, 'Casting')){
        $castindex = $castindex + 1;
    }

    $rs = mysql_query("SELECT id, last, first FROM Director, MovieDirector WHERE Director.id = MovieDirector.did AND MovieDirector.mid = $mid;", $db_connection);
    while($directors[$directorindex] = mysql_fetch_object($rs, 'Director')){
        $directorindex = $directorindex + 1;
    }

    $rs = mysql_query("SELECT genre FROM MovieGenre WHERE mid = $mid;", $db_connection);
    while($genres[$genreindex] = mysql_fetch_object($rs, 'Genre')){
        $genreindex = $genreindex + 1;
    }

    $rs = mysql_query("SELECT imdb, rt FROM MovieRating WHERE mid = $mid;", $db_connection);
    while($ratings[$ratingindex] = mysql_fetch_object($rs, 'Rating')){
        $ratingindex = $ratingindex + 1;
    }

    $rs = mysql_query("SELECT name, time, rating, comment FROM Review WHERE mid = $mid;", $db_connection);
    while($reviews[$reviewindex] = mysql_fetch_object($rs, 'Review')){
        $reviewindex = $reviewindex + 1;
    }

    mysql_close($db_connection);

    //AVERAGE CALCULATIONS-----------------------------------------------
    $ratingSum = 0;
    foreach($reviews as $review){
        $ratingSum += $review->rating;
    }
    $avgReview = $ratingSum/(sizeof($reviews)-1);
    
    //HTML OUTPUT--------------------------------------------------------
    //Movie information
    echo "<div class='container'><div class='row'>";
    echo "<div class='col-md-8'><h1 class='movie-title'>".$movie->title."</h1>";
    echo "<strong>".$movie->rating."</strong><br>";
    echo "<i>".$movie->company.", ".$movie->year."</i><br>";
    echo "Genres: ";
    foreach($genres as $key => $genre){
        if($genre){
            if($key == 0){
                echo $genre->genre;
            }
            else{
                echo ' | '.$genre->genre;
            }
            
        }
    }
    echo "<br>";
    if($directorindex > 0){
        echo "Directed by ";
        foreach($directors as $key => $director){
            if($director){
                if($key == 0){
                    echo "<a href='./person.php?id=".$director->id."'>".$director->first." ".$director->last."</a>";
                }
                else{
                    echo ', '."<a href='./person.php?id=".$director->id."'>".$director->first." ".$director->last."</a>";
                }
                
            }
        }
    }
    else{
        echo "No directors known.";
    }
    echo  "<small><a href = './dirtomovie.php?mid=".$mid."'> Add a director...</a></small>";
    echo "</div>"; //End of movie info block
    echo "<div class='col-md-4'>";
    echo '
    <div class="square">
        <div class="square-content">
            <small>Average user review</small>
            <div class="square-table">
                <div class="square-table-cell">';
                if(sizeof($reviews) > 1){echo number_format($avgReview, 1);}
                else{echo "N/A";}
                echo '</div>
            </div>
        </div>
    </div>';
    echo "</div></div>"; //End of movie info block

    //Cast information
    foreach ($cast as $actor){
        if($actor){
            echo "<pre>";
            echo "<div class = 'pull-left'><a href='./person.php?id=".$actor->aid."'>".$actor->first." ".$actor->last."</a> as ".$actor->role."<br></div>";
            echo "</pre>";
        }
        
    }
    //Add actors
    echo "<div class = 'pull-right'><a href='./actortomovie.php?mid=".$mid."'> Add another actor...</a></div><br/>";

    //Reviews
    foreach ($reviews as $review){
        if($review){
            echo "<hr />";
            echo "<strong>".$review->name."</strong> on ".date('F j, Y, g:i A',strtotime($review->time))." rated this a ".$review->rating.", saying:<br/>";
            echo $review->comment;
            echo "<br/>";
        }
        
    }
    echo "<hr />";

    echo '
        <button type="button" class="btn review-button" data-toggle="collapse" data-target="#comment-form">Add a new review</button>
        <div class="panel panel-default collapse"  id="comment-form"> 
            <div class="panel-body">
            <FORM METHOD = "GET" ACTION = "./movie.php"  >
            <input type="hidden" NAME="mid" value="'.$mid.'" />
            <input type="hidden" NAME="date" value="'.date('Y-m-d').'"/>
            <div class="form-group">
                <label for="usernameInput">Your name</label>
                <input class="form-control" NAME="cmname" id="usernameInput" placeholder="Username">
            </div>
            <div class="form-group">
                <label for="ratingSelect">Rating</label>
                <select class="form-control" id="ratingSelect" NAME="rating">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
                </select>
            </div>
            <div class="form-group">
                <label for="commentInput">Comments</label>
                <textarea class="form-control" NAME="comment" id="commentInput" VALUE="default" placeholder="What did you think of this movie?" rows="5" maxlength="500"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            </FORM>
            </div>
        </div>
        </div>';

    echo "</div>"; //container
    
}
?>
<br>
<br>
</body>
</html>