<?php
//outbox.php
include_once 'class_query.php';
$selectQuery = new MysqlQuery();
//link menu
include_once "menu.php";

//query ke tabel outbox
$table 	= "outbox";
$field_db	= "*";
$result = $selectQuery->getRecord($table, $field_db);

if ($result ) {
	echo "<h2> SMS yang masih dalam Antrian </h2>";
	echo "	<table>
						<thead>
							<tr>
								<th>Tgl Kirim</th>
								<th>No Tujuan</th>
								<th>Pesan</th>
							</tr>
						</thead>
						<tbody>";

    while ($row = $result->fetch_assoc()) {
			echo "	<tr>
								  <td>".$row["SendingDateTime"]."</td>
								  <td>".$row["DestinationNumber"]."</td>
								  <td>".$row["TextDecoded"]."</td>	  
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