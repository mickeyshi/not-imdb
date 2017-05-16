<html>
<head>
    <title>!mdb</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="./styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="./styles.css">
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
<div class='container'>
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
    class Director{
        public $id;
        public $last;
        public $first;
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
    class DirectorCredit{
        public $did;
        public $title;
        public $year;
        public $rating;
        public $company;
    }
    $actor = new Actor;
    $roles = array();
    $roleindex = 0;
    $directorcredits = array();
    $directorcreditindex = 0;
    
    //MYSQL FETCHING--------------------------------------------------------
    $db_connection = mysql_connect("localhost", "cs143", "");
    if(!$db_connection) {
        $errmsg = mysql_error($db_connection);
        echo "Connection failed:" . $errmsg . "<br />";
        exit(1);
    }
    mysql_select_db("CS143", $db_connection);
    $id = $_GET["id"];

    $rs = mysql_query("SELECT * FROM Actor WHERE id = $id", $db_connection);
    $actor = mysql_fetch_object($rs, 'Actor');

    //Check if the person is also a director
    if(!$actor){
      $rs = mysql_query("SELECT * FROM Director WHERE id = $id", $db_connection);
      $actor = mysql_fetch_object($rs, 'Director');
    }
    
    $rs = mysql_query("SELECT mid, role, title, year, rating, company FROM Actor, MovieActor, Movie WHERE Actor.id = $id AND Actor.id = MovieActor.aid AND MovieActor.mid = Movie.id ORDER BY year DESC;", $db_connection);
    while($roles[$roleindex] = mysql_fetch_object($rs, 'Role')){
        $roleindex = $roleindex + 1;
    }

    $rs = mysql_query("SELECT mid, title, year, rating, company FROM Director, MovieDirector, Movie WHERE Director.id = $id AND Director.id = MovieDirector.did AND MovieDirector.mid = Movie.id ORDER BY year DESC;", $db_connection);
    while($directorcredits[$directorcreditindex] = mysql_fetch_object($rs, 'Director')){
        $directorcreditindex = $directorcreditindex + 1;
    }

    mysql_close($db_connection);

    //HTML OUTPUT--------------------------------------------------------
    //Actor information
    echo "";
    echo "<h1 class='person-name'>".$actor->first." ".$actor->last."</h1>";
    echo "<i>";
    echo date('F j\, Y', strtotime($actor->dob))." - ";
    if($actor->dod){
        echo date('F j\, Y', strtotime($actor->dod));
    }
    echo "</i><br>";
    //Movie information
    if($roleindex > 0){echo "<h4 class='display-3'>Actor credits</h4>";}
    foreach ($roles as $role){
        if($role){
            echo "<pre>";
            echo "<div class = 'pull-left'><a href='./movie.php?mid=".$role->mid."'>".$role->title."</a> as ".$role->role."<br><small>".$role->company."</small></div>";
            echo "<div class = 'pull-right'>".$role->year."</div>";
            echo "</pre>";
        }
    }
    if($directorcreditindex > 0){echo "<h4 class='display-3'>Director credits</h4>";}
    foreach ($directorcredits as $dc){
        if($dc){
            echo "<pre>";
            echo "<div class = 'pull-left'><a href='./movie.php?mid=".$dc->mid."'>".$dc->title."</a><br><small>".$dc->company."</small></div>";
            echo "<div class = 'pull-right'>".$dc->year."</div>";
            echo "</pre>";
        }
    }
    
//}
?>
</div>
</body>
</html>