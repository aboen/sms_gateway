<?php
//inbox.php
include_once 'class_query.php';
$selectQuery = new MysqlQuery();

//query ke tabel inbox
$table 	= "inbox WHERE Processed = 'false'  ";
$field_db	= "*";
$result = $selectQuery->getRecord($table, $field_db);

// tentukan format pemisah 
//misal format pengiriman adalah : 
//untuk daftar : REG#Nama#ALamat#Password
//untuk membatalkan member : UNREG#Password
//untuk informasi yang sudah daftar : INFO
$pemisah = "#";

if ($result ) {
	while ($row = $result->fetch_assoc()) {
		//pecah sms yangmasuk dengan explode
		$pecah = explode($pemisah,$row['TextDecoded']);
		
		//jadikan huruf besar untuk format kata pertama agar mudah dalam memfilter format
		//bila member menggunakan huruf kecil maka otomatis akan diubah ke huruf besar semua 
		$filter= strtoupper($pecah[0]);
	
		//filter berdasarkan Kata pertama 
			switch ($filter) {
				// Register	
				case 'REG' :
					$nohp = $row['SenderNumber'];
					$nama = $pecah[1];
					$alamat = $pecah[2];
					$password = md5($pecah[3]);
					
					//Perikasa apakah nomor sudah terdaftar
					$nohp = $row['SenderNumber'];
					$table 	= "members";
					$table   .=" WHERE NoHp = '$nohp' ";
					$field_db	= "Nama,NoHp";
					$resultHp = $selectQuery->getRecord($table, $field_db);
					$recnohp= $resultHp->fetch_assoc();
					if ($nohp === $recnohp['NoHp']) {

							//Balas SMS pendaftaran  jika sudah terdaptar
							$tableo 	= "outbox";
							$field_dbo	= "DestinationNumber,TextDecoded";
							$textsmso = "[ERROR] $nama , Nomor HP $nohp sudah terdaftar atas nama :". $recnohp['Nama'] ;
							$fieldValueo =  " '".$row['SenderNumber']."' ,  ' " . $textsmso. " ' ";
							$selectQuery->insertQuery($tableo, $field_dbo, $fieldValueo);
							
						} else {
							
							//masukan ke db members
							$tablem	=  "members";
							$field_dbm	=  "Nama,NoHp,Alamat,Password,Status";
							$fieldValuem = " '$nama','$nohp','$alamat','$password','1' ";
							$selectQuery->insertQuery($tablem,$field_dbm,$fieldValuem);
							
							//update inbox dan set Processed = 'true'
							//agar sms yang berada pada inbox tidak di periksa kembali karena statusnya sudah true
							$table = "inbox";
							$fieldUpdate =  " Processed = 'true' ";
							$field = "ID";
							$operator = $row['ID'];
							$selectQuery->updateQuery($table, $fieldUpdate, $field, $operator);
							
							//Balas SMS pendaftaran 
							$tableo 	= "outbox";
							$field_dbo	= "DestinationNumber,TextDecoded";
							$textsmso = "[REG] Terima kasih $nama, sudah mendaftar di layanan ini ";
							$fieldValueo =  " '".$row['SenderNumber']."' ,  ' " . $textsmso. " ' ";
							$selectQuery->insertQuery($tableo, $field_dbo, $fieldValueo);
						
						}	
				 break;
				 
				// Membatalkan members 
				case 'UNREG' :
					$password =md5($pecah[1]);
					$nohp = $row['SenderNumber'];
					$table 	= "members";
					$table   .=" WHERE NoHp = '$nohp' ";
					$field_db	= "ID,Nama,Password";
									
					$result = $selectQuery->getRecord($table, $field_db);
					$pass= $result->fetch_assoc();
					if ($password === $pass['Password']) {
						//update inbox dan set Status = '0' atau hapus data pada tabel member
							$table = "members";
							$fieldUpdate =  " Status = '0' ";
							$field = "ID";
							$operator = $pass['ID'];
						//set status mejadi 0 
							//$selectQuery->updateQuery($table, $fieldUpdate, $field, $operator);
						//jika ingin menghapus pakai query yang bawah ini
							$selectQuery->deleteQuery($table, $field, $operator);
						
						//update inbox dan set Processed = 'True'
							$table = "inbox";
							$fieldUpdate =  " Processed = 'true' ";
							$field = "ID";
							$operator = $row['ID'];
							$selectQuery->updateQuery($table, $fieldUpdate, $field, $operator);
						//Balas SMS UNREG
							$table 	= "outbox";
							$field_db	= "DestinationNumber,TextDecoded";
							$textsms = " [UNREG] Terima kasih ". $pass['Nama'].", Anda sudah membatalkan ke anggotaan ";
							$fieldValue =  " '".$row['SenderNumber']."' ,  ' " . $textsms. " ' ";
							$selectQuery->insertQuery($table, $field_db, $fieldValue);
					}
				break;
				
				case 'INFO' :
						//Ambil data pada members
						$nohp = $row['SenderNumber'];
						$table 	= "members ";
						$table   .="  WHERE NoHp = '$nohp' ";
						$field_db	= "Nama,NoHp,Alamat";
						$info = $selectQuery->getRecord($table, $field_db);
						$infomembers= $info->fetch_assoc();
						
						if ($nohp == $infomembers['NoHp']) {
							//Balas SMS INFO
								$table 	= "outbox";
								$field_db	= "DestinationNumber,TextDecoded";
								//isi pesan sms
								$textsms = "[INFO] Nama: ". $infomembers['Nama'].", Nomor HP: ". $infomembers['NoHp'].", Alamat: ". $infomembers['Alamat'];
								$fieldValue =  " '".$row['SenderNumber']."' ,  ' " . $textsms. " ' ";
								$selectQuery->insertQuery($table, $field_db, $fieldValue);
								
						} else {
							//Balas SMS INFO bila Nomor HP belum ditemukan
								$table 	= "outbox";
								$field_db	= "DestinationNumber,TextDecoded";
								$textsms = "[ERROR]  Nomor  :". $row['SenderNumber'].", Belum terdaftar ";
								$fieldValue =  " '".$row['SenderNumber']."' ,  ' " . $textsms. " ' ";
								$selectQuery->insertQuery($table, $field_db, $fieldValue);
						}
							//update inbox dan set Processed = 'True'
								$table = "inbox";
								$fieldUpdate =  " Processed = 'true' ";
								$field = "ID";
								$operator = $row['ID'];
								$selectQuery->updateQuery($table, $fieldUpdate, $field, $operator);
				break;
			}
	}
}	
?>