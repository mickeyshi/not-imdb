<html>
<head>
    <title>Movie Database Querying Service</title>
    <!--
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    -->
</head>
<body>
<h1>Movie Database Querying Service</h1>
<FORM METHOD = "GET" ACTION = "./query.php">
<textarea rows="4" cols ="50" NAME="query" VALUE="default" SIZE=10 placeholder="Enter SQL query here.">
</textarea>
<INPUT TYPE="submit" VALUE="Submit query">
</FORM>

<br>

<?php if($_GET["query"]){
    

    $db_connection = mysql_connect("localhost", "cs143", "");
    if(!$db_connection) {
        $errmsg = mysql_error($db_connection);
        echo "Connection failed:" . $errmsg . "<br />";
        exit(1);
    }

    mysql_select_db("CS143", $db_connection);
    $query = $_GET["query"];
    //$query = mysql_real_escape_string($_POST["query"], $db_connection);
    echo "Your SQL query was: <br>";
    echo $query;
    echo "<br>";
    $rs = mysql_query($query, $db_connection);

    $result = array();
    $index = 0;
    echo "The output of your SQL query is: <br>";
    echo "<table class='table' cellspacing='1' cellpadding='2' border='1'>";
        $i = 0;
    echo "<thead><tr>";
    while ($i < mysql_num_fields($rs)) {
        $meta = mysql_fetch_field($rs, $i);
        echo "<th>$meta->name</th>";
                $i++;
    }
    echo "</tr></thead><tbody>";
    while($row = mysql_fetch_row($rs)){
        $result[$index] = $row;
        $index = $index + 1;

        echo "<tr>";
        for($j = 0; $j < count($row); $j++){
            echo "<td>";
            echo $row[$j];
            echo "</td>";
        }
        echo "</tr>";
    }
    echo"</tbody></table>";
    mysql_close($db_connection);
}
?>
</body>
</html>