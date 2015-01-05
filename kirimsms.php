<?php
//kirimsms.php
include_once 'class_query.php';
$selectQuery = new MysqlQuery();
//link menu
include_once "menu.php";

//proses hasil dari form kirim ke tabel outbox
if (isset($_POST['kirim'])  AND !empty($_POST['DestNum']) AND !empty($_POST['textsms'])) {
	$textsms = addslashes($_POST['textsms']);
	$table 	= "outbox";
	$field_db	= "DestinationNumber,TextDecoded";
	$fieldValue =  " '".$_POST['DestNum']."' ,  ' " . $textsms. " ' ";
	
	$result = $selectQuery->insertQuery($table, $field_db, $fieldValue);
	
}

?>
<html>
<head>
<title>Kirim SMS </title>
</head>
</body>
<h2 >Kirim SMS</h2>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<table>
					<tr>
						<td>Nomor Tujuan</td>
						<td><input name="DestNum" type="text"  ></td>
					</tr>
				  	 <tr>
						<td>Pesan SMS</td>
						<td><Textarea name="textsms"  type="text"   rows="6"></textarea></td>
				  	</tr >
					<tr>
						<td colspan=2>	<button type="submit" name="kirim" >Kirim</button></td>
					</tr >
				</table>
			</form>
</body>
</html>

<?php
/* close connection */
$mysqli->close();
?>