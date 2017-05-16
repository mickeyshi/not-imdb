<html>
<head>
    <title>!mdb</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="./styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <link href="https://fonts.googleapis.com/css?family=Archivo+Narrow|Cabin+Condensed|Khand" rel="stylesheet"> 
    <!--
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    -->
  <script>
  $( function() {
    $('.datepicker').datepicker({changeYear: true, yearRange : '1900:2010'});
  } );
  </script>
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
<?php
    //CLASS DECLARATIONS----------------------------------------------------
    class Actor{
        public $id;
        public $last;
        public $first;
        public $sex;
        public $dob;
        public $dod;
    }
    class Movie{
        public $id; 
        public $title;
        public $year; 
        public $rating; 
        public $company;
    }
    $currmovie;
    $movies = array();
    $movieindex = 0;
    $actors = array();
    $actorindex = 0;

    $mid = $_GET["mid"];
    //MYSQL FETCHING--------------------------------------------------------
    $db_connection = mysql_connect("localhost", "cs143", "");
    if(!$db_connection) {
        $errmsg = mysql_error($db_connection);
        echo "Connection failed:" . $errmsg . "<br />";
        exit(1);
    }
    
    mysql_select_db("CS143", $db_connection);

    if($mid){
         $rs = mysql_query("SELECT * FROM Movie WHERE id = ".$mid.";", $db_connection);
         if(!$currmovie = mysql_fetch_object($rs, 'Movie')){
            echo 'Movie was not found.';
         }
    }
    else{
        $rs = mysql_query("SELECT * FROM Movie;", $db_connection);
        while($movies[$movieindex] = mysql_fetch_object($rs, 'Movie')){
            $movieindex = $movieindex + 1;
        }
    }

    if($_GET["submit"]){
        $insert = "INSERT INTO MovieActor VALUES (".$_GET["mid"].",".$_GET["aid"].",\"".$_GET["role"]."\");";
        //echo $insert;
        if($rs = mysql_query($insert, $db_connection)){
            echo '<div class="alert alert-success alert-dismissable">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  Person successfully added to <a href="./movie.php?mid='.$mid.'">'.$currmovie->title.'</a>
              </div>';
        }
        //$id = mysql_fetch_object($rs)->id + 1;
    }

    $rs = mysql_query("SELECT * FROM Actor;", $db_connection);
    while($actors[$actorindex] = mysql_fetch_object($rs, 'Actor')){
        $actorindex = $actorindex + 1;
    }
    echo '<h3>Add an actor';
    if($mid){
        echo ' to <em>'.$currmovie->title.'</em>';
    }
    echo '</h3>
    <div class="panel panel-default">
    <div class="panel-body">
        <form METHOD = "GET" ACTION = "./actortomovie.php">
        <input class="form-control" type="hidden" NAME="submit" value="y" />';
    if($mid){
        echo '<input class="form-control" type="hidden" NAME="mid" value="'.$mid.'" />';
    }
    else{
        echo '
        <div class="form-group">
            <label for="movie">Movie</label>
            <select class="form-control" id="movie" name="mid">';
        foreach($movies as $movie){
            echo '<option value='.$movie->id.'>'.$movie->title.'('.$movie->year.')</option>';
        }
        echo '</select></div>';
    }
    echo '
        <div class="form-group">
            <label for="Actor">Actor</label>
            <select class="form-control" id="actor" name="aid">';
    foreach($actors as $actor){
        echo '<option value='.$actor->id.'>'.$actor->first.' '.$actor->last.'</option>';
    }
    echo '
        </select>
        </div>
        <div class="form-group">
            <label class="control-label" for="role">
            Role
            </label>
            <input class="form-control" id="role" name="role" type="text"/>
        </div>
        <div class="form-group">
        <div>
        <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        </div>
    </div>
    </div>
        ';   
?>


<?php
    
    $actor = new Actor;
    
    while($roles[$roleindex] = mysql_fetch_object($rs, 'Role')){
        $roleindex = $roleindex + 1;
    }

    //var_dump($actor);
    //var_dump($roles);
    mysql_close($db_connection);
    
?>
</div>
</body>

</html>