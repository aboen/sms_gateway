<?php
//inbox.php
include_once 'class_query.php';
$selectQuery = new MysqlQuery();
//link menu
include_once "menu.php";

//Hapus inbox
if(isset($_GET['del'])) {
	$table = "inbox";
	$field = "ID";
	$record = $_GET['del'];
	$result = $selectQuery->deleteQuery($table,$field,$record);

}

//query ke tabel inbox
$table 	= "inbox";
$field_db	= "*";
$result = $selectQuery->getRecord($table, $field_db);

if ($result ) {
	echo "	<table>
						<thead>
							<tr>
									<th>[ Tgl Terima ]</th>
									<th>[ No Pengirim ]</th>
									<th>[ Pesan ]</th>
									<th>[ Proses ]</th>
									<th>[ Aksi ]</th>
							</tr>
						</thead>
						<tbody>";

    while ($row = $result->fetch_assoc()) {
			echo "	<tr>
								  <td>".$row["ReceivingDateTime"]."</td>
								  <td>".$row["SenderNumber"]."</td>
								  <td>".$row["TextDecoded"]."</td>
								  <td>".$row["Processed"]."</td>
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