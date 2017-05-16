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
<?php
       //MYSQL FETCHING--------------------------------------------------------
    $db_connection = mysql_connect("localhost", "cs143", "");
    if(!$db_connection) {
        $errmsg = mysql_error($db_connection);
        echo "Connection failed:" . $errmsg . "<br />";
        exit(1);
    }
    mysql_select_db("CS143", $db_connection);
    //$aid = 9373;
    $aid = $_GET["aid"];

    //$query = mysql_real_escape_string($_POST["query"], $db_connection);
    if($_GET["submit"]){
        $rs = mysql_query("SELECT * FROM MaxPersonID", $db_connection);
        $id = mysql_fetch_object($rs)->id + 1;
        
        $insert = "INSERT INTO ".$_GET["type"]." VALUES (".$id.", \"".$_GET["last"]."\" , \"".$_GET["first"]."\" ,";
        //echo (strcmp($_GET["type"],'Actor '));
        if(!strcmp($_GET["type"],'Actor')){
          $insert = $insert."\"".$_GET["sex"]."\" , ";
        }
        $insert = $insert.date("Ymd", strtotime($_GET["dob"]));
        if($_GET["dod"]){
            $insert = $insert." , ".date("Ymd", strtotime($_GET["dod"]));
        }
        else{
            $insert = $insert.", \\N";
        }
        
        $insert = $insert.");";
        //echo $insert;
        $rs = mysql_query("UPDATE MaxPersonID SET id = id+1;", $db_connection);
        if($rs){
          $rs = mysql_query($insert, $db_connection);
          if($rs){
            echo '<div class="alert alert-success alert-dismissable">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  Person successfully added to database. <a href="./person.php?id='.$id.'">'.$_GET["first"].' '.$_GET["last"].'</a>
              </div>';
          }
        }
        else{
          echo "Unable to update MaxPersonID";
        }
    }
?>
<div class='container'>
<h3>Add an actor/director </h3>
<div class="panel panel-default">
  <div class="panel-body">
    <form METHOD = "GET" ACTION = "./addperson.php">
     <input class="form-control" type="hidden" NAME="submit" value="y" />
     <div class="row">
        <div class="form-group col-xs-4">
        <label class="control-label " for="first">
        First name
        </label>
        <input class="form-control" id="first" name="first" type="text"/>
        </div>
        <div class="form-group col-xs-4">
        <label class="control-label " for="last">
        Last name
        </label>
        <input class="form-control" id="last" name="last" type="text"/>
        </div>
    </div>
    <div class="form-group" id="div_type">
      <label class="control-label " for="type">
       Person type
      </label>
      <div class="">
       <label class="radio-inline">
        <input name="type" type="radio" value="Actor"/>
        Actor
       </label>
       <label class="radio-inline">
        <input name="type" type="radio" value="Director"/>
        Director
       </label>
      </div>
     </div>
     <div class="form-group" id="div_sex">
      <label class="control-label " for="sex">
       Gender
      </label>
      <div class="">
       <label class="radio-inline">
        <input name="sex" type="radio" value="Male"/>
        Male
       </label>
       <label class="radio-inline">
        <input name="sex" type="radio" value="Female"/>
        Female
       </label>
      </div>
     </div>
     <div class="form-group"> <!-- Date input -->
        <label class="control-label" for="datepicker">Date of birth</label>
        <input class="form-control datepicker" type="text" id="dob" name = "dob" placeholder="MM/DD/YYYY">
      </div>
     <div class="form-group">
    <div class="form-group"> <!-- Date input -->
        <label class="control-label" for="datepicker">Date of death (leave empty if not deceased)</label>
        <input class="form-control datepicker" type="text" id="dod" name = "dod" placeholder="MM/DD/YYYY">
      </div>
     <div class="form-group">
      <div>
      <button type="submit" class="btn btn-primary">Submit</button>
      </div>
     </div>
    </form>
  </div>
</div>
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