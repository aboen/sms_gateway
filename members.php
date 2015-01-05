<?php
//inbox.php
include_once 'class_query.php';
$selectQuery = new MysqlQuery();
//link menu
include_once "menu.php";

//Hapus inbox
if(isset($_GET['del'])) {
	$table = "members";
	$field = "ID";
	$record = $_GET['del'];
	$result = $selectQuery->deleteQuery($table,$field,$record);
}

//query ke tabel inbox
$table 	= "members";
$field_db	= "*";
$result = $selectQuery->getRecord($table, $field_db);

if ($result ) {
	echo "	<table>
						<thead>
							<tr>
									<th>[ Nama ]</th>
									<th>[ No Handphone ]</th>
									<th>[ Alamat ]</th>
									<th>[ Password ]</th>
									<th>[ Status ]</th>
									<th>[ Aksi ]</th>
							</tr>
						</thead>
						<tbody>";

    while ($row = $result->fetch_assoc()) {
			echo "	<tr>
								  <td>".$row["Nama"]."</td>
								  <td>".$row["NoHp"]."</td>
								  <td>".$row["Alamat"]."</td>
								  <td>".$row["Password"]."</td>
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