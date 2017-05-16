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
<div class="container">
    <h1>Search results</h1>
<?php /*if($_GET["aid"]){*/

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
    class Movie{
        public $id; 
        public $title;
        public $year; 
        public $rating; 
        public $company;
    }
    class Role{
        public $mid;
        public $role;
        public $title;
        public $year;
        public $rating;
        public $company;
    }
    $actors = array();
    $actorindex = 0;
    $movies = array();
    $movieindex = 0;
    //MYSQL FETCHING--------------------------------------------------------
    $db_connection = mysql_connect("localhost", "cs143", "");
    if(!$db_connection) {
        $errmsg = mysql_error($db_connection);
        echo "Connection failed:" . $errmsg . "<br />";
        exit(1);
    }
    mysql_select_db("CS143", $db_connection);
    //$aid = 9373;
    $searchquery = $_GET["query"];
    $keywords = array();
    $keywords = explode(' ', $searchquery);
    //$query = mysql_real_escape_string($_POST["query"], $db_connection);
    $query = '';
    if($keywords[0]){
        //Fetch Actors
        $query = "SELECT * FROM Actor WHERE (last LIKE '%".$keywords[0]."%' OR first LIKE '%".$keywords[0]."%')";
        for($i = 1; $i < sizeof($keywords); $i++){
            $query = $query." AND (last LIKE '%".$keywords[$i]."%' OR first LIKE '%".$keywords[$i]."%')";
        }
        $query = $query.'ORDER BY last';
        $rs = mysql_query($query, $db_connection);
        while($actors[$actorindex] = mysql_fetch_object($rs, 'Actor')){
            $actorindex = $actorindex + 1;
        }

        //Fetch Movies
         $query = "SELECT * FROM Movie WHERE title LIKE '%".$keywords[0]."%'";
        for($i = 1; $i < sizeof($keywords); $i++){
            $query = $query." AND title LIKE '%".$keywords[$i]."%'";
        }
        //echo $query;
        $query = $query.'ORDER BY title';
        $rs = mysql_query($query, $db_connection);
        while($movies[$movieindex] = mysql_fetch_object($rs, 'Movie')){
            $movieindex = $movieindex + 1;
        }
    }
    else{
        echo "Please input a search query.";
    }
    
    //var_dump($movies);
    //var_dump($actors);
    mysql_close($db_connection);

    //HTML OUTPUT--------------------------------------------------------
    echo "Results for the query <em> \"".$searchquery."\"</em>";

    //Actors
    //Actor information
    echo "<div class = 'row'>";
    echo "<div class='col-md-6'><h3>Actors</h3>";
    if(sizeof($actors) == 1){
        echo "<em>No actors matching the query found.</em>";
    }
    foreach ($actors as $actor){
        if($actor){
            echo "<pre>";
            echo "<div class = 'pull-left'><a href='./person.php?id=".$actor->id."'>".$actor->last.",".$actor->first."</a></div>";
            //echo "<div class = 'pull-right'>".$role->year."</div>";
            echo "</pre>";
        }
    }
    echo "</div><div class='col-md-6'><h3>Movies</h3>";
    //Movies
    if(sizeof($movies) == 1){
        echo "<em>No movies matching the query found.</em>";
    }
    foreach($movies as $movie){
        if($movie){
            echo "<pre>";
            echo "<div class = 'pull-left'><a href='./movie.php?mid=".$movie->id."'>".$movie->title."</a></div>";
            //echo "<div class = 'pull-right'>".$role->year."</div>";
            echo "</pre>"; 
        }
    }
    echo "</div></div>";
    
?>
</div>
</body>
</html>