<?php
    $type = rand(0, 1);
    //MYSQL FETCHING--------------------------------------------------------
    $db_connection = mysql_connect("localhost", "cs143", "");
    if(!$db_connection) {
        $errmsg = mysql_error($db_connection);
        echo "Connection failed:" . $errmsg . "<br />";
        exit(1);
    }
    mysql_select_db("CS143", $db_connection);
    switch($type){
        case 0:
             $rs = mysql_query("SELECT id FROM Movie ORDER BY RAND() LIMIT 1;", $db_connection);
             $id = mysql_fetch_object($rs)->id;
             header("Location: ./movie.php?mid=".$id);
             die();
            break;
        case 1:
             $rs = mysql_query("SELECT id FROM Actor ORDER BY RAND() LIMIT 1;", $db_connection);
             $id = mysql_fetch_object($rs)->id;
             header("Location: ./person.php?id=".$id);
             die();
            break;
    }
?>