<?php

$db_host = 'localhost';
$db_user = 'elpalgnt_cse385';
$db_pass = ')-F^13xW4XDp';
$db_name = 'elpalgnt_cse385_db';


// allows this php file to act as a remote API
header('Access-Control-Allow-Origin: *'); 


if(!isset($_REQUEST['q'])){
	die('{"status":"error","message":"Missing query in request"}');
}

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
// Check connection
if ($conn->connect_error) {
    die('{"status":"error","message":"Error connecting to the database"}');
} 

$sql = $_REQUEST['q'];

$result = mysqli_query($conn, $sql);


if(!$result){
	die('{"status":"error","message":"'.mysqli_error($conn).'"}');
}

// ======== Query executed successfully, begin constructing return data ============

$ret = '{"status":"success","columns":[ ';

$first = true;
while ($meta = mysqli_fetch_field($result)) {

    // handle comma separating
    if($first)
    	$first = false;
    else
    	$ret .= ', ';

    $ret .= '"'.$meta->name.'"';
}

$ret .= ' ],"data":[ ';

$first = true;
while($row = mysqli_fetch_row ($result)) {
	if($first)
		$first = false;
	else
		$ret .= ', ';

	$ret .= '[';

	$first2 = true;
	foreach ($row as $element){
		if($first2)
			$first2 = false;
		else
			$ret .= ', ';

		$ret .= '"'.str_replace('"','\"',$element).'"';
	}

	$ret .= ']';
}

$ret .= ' ]}';

echo $ret;

?>