<?php
$conn = mysqli_connect('localhost', 'root', '','market');
if (!$conn) {
  die('Could not connect: ' . mysql_error());
}else{
    mysqli_select_db($conn, 'market');
}

$query="SELECT * FROM company WHERE symbol != '' AND mmbURL = '' AND status !='8' ORDER BY id DESC LIMIT 100";
$symbol = array();
$result = mysqli_query($conn, $query);
// Assume you will have more than one row in the recordset
//$row = mysqli_fetch_object($result);
//$row = mysqli_fetch_all($result,MYSQLI_ASSOC);
$i = 1;
while ($row = mysqli_fetch_array($result)) {
 echo "<br>$i)".$row['symbol'].": ";
 $id = $row['id'];
 $url = "https://mmb.moneycontrol.com/index.php?q=home/ajax_call&section=get_search_data&searchStr=".$row['symbol']."&srch_typ=All";

	$file = json_decode(file_get_contents($url));
	foreach($file as $v){
		print_r($v->url);
		$sql = "UPDATE company SET mmbURL='$v->url',mmbID='$v->id' WHERE id='$id'";
		$conn->query($sql);
		break;
	}
	//$sql = "UPDATE company SET baseurl='$baseurl',baseid='$baseid' WHERE id='$id'";
	//$conn->query($sql);
	$i++;
}
/*
$sql = "UPDATE company SET baseurl='$baseurl',baseid='$baseid' WHERE id='$id'";
if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

/*while ($row = mysqli_fetch_object($result)) {
    echo $row->symbol;
}

mysqli_free_result($result);*/
//$row = mysqli_free_result($result);

//print_r(json_encode($row));

//while ($row = mysqli_fetch_array($result)) {
// echo $row['symbol'].'<br />';
// }
//$result = mysql_query($query,$conn);
//echo $result;
//$r=mysql_fetch_row($result);
?>