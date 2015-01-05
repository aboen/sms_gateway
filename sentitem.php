<?php
//sentitem.php
include_once 'class_query.php';
$selectQuery = new MysqlQuery();
//link menu
include_once "menu.php";

//Hapus sentitems
if(isset($_GET['del'])) {
	$table = "sentitems";
	$field = "ID";
	$record = $_GET['del'];
	$result = $selectQuery->deleteQuery($table,$field,$record);
	
}

//query ke tabel sentitems
$table 	= "sentitems";
$field_db	= "*";
$result = $selectQuery->getRecord($table, $field_db);

if ($result) {
		echo "<h2> SMS yang sudah Terkirim </h2>";
	echo "	<table>
						<thead>
							<tr>
									<th>[ waktu Pengiriman ]</th>
									<th>[ waktu Kirim ]</th>
									<th>[ No Tujuan ]</th>
									<th>[ Pesan ]</th>
									<th>[ Status ]</th>
									<th>[ Aksi ]</th>
							</tr>
						</thead>
						<tbody>";


    while ($row = $result->fetch_assoc()) {
			echo "	<tr>
									<td>".$row["InsertIntoDB"]."</td>
									<td>".$row["SendingDateTime"]."</td>								  
									<td>".$row["DestinationNumber"]."</td>
									<td>".$row["TextDecoded"]."</td>
									<td>".$row["Status"]."</td>
									<td><a href='". $_SERVER['PHP_SELF']."?del=".$row['ID']."'>Hapus</a></td>  
							</tr>";
    }
	echo "		</tbody>
					</table>";
}

/* free result set */
$result->free();
/* close connection */
$mysqli->close();
?>