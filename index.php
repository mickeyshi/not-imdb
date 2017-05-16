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
    class MovieStat{
        public $id; 
        public $title;
        public $year; 
        public $rating; 
        public $company;
        public $imdb;
        public $rot;
    }
    
    $actor = new Actor;
    $moviestats = array();
    $moviestatindex = 0;
    //MYSQL FETCHING--------------------------------------------------------
    $db_connection = mysql_connect("localhost", "cs143", "");
    if(!$db_connection) {
        $errmsg = mysql_error($db_connection);
        echo "Connection failed:" . $errmsg . "<br />";
        exit(1);
    }
    mysql_select_db("CS143", $db_connection);

    $rs = mysql_query("SELECT * FROM Movie, MovieRating WHERE mid = id ORDER BY imdb DESC LIMIT 10;", $db_connection);
    while($moviestats[$moviestatindex] = mysql_fetch_object($rs, 'MovieStat')){
        $moviestatindex = $moviestatindex + 1;
    }

    mysql_close($db_connection);
    //HTML OUTPUT--------------------------------------------------------
    //Actor information
    //Movie information
    echo "<div class='row'>";
    echo "<div class='col-md-8'><div class='jumbotron'>";
    echo '<h1>Definitely Not iMDB</h1>
      <p>More accurate description of website forthcoming.</p>
      
      <p>
         <a class = "btn btn-primary btn-lg" role = "button">Learn more</a>
      </p>';
    echo "</div></div>";
    echo "<div class='col-md-4'><h4></h4>";
    echo "<div class = 'panel panel-default'><div class='panel-heading'>Highest rated films<div class='panel-body'>";
    foreach ($moviestats as $moviestat){
        if($moviestat){
            echo "<div class='row'>";
            echo "<div class = 'pull-left'><a href='./movie.php?mid=".$moviestat->id."'>".$moviestat->title."</a><br><small>".$moviestat->company."</small></div>";
            echo "<div class = 'pull-right'>".$moviestat->imdb."</div>";
            echo "</div></hr>";
        }
    }
    echo "</div></div></div>";
//}
?>
</div>
</body>
</html>