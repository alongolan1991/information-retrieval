<?php

include "wordDBhandler.php";

$db = initDB();

$file = $_GET['remove_file'];
$filename = 'DB/'.$file;

if(is_writable($filename)) {
	inactiveDocFromDB($db, $file);
}

saveDB($db);

header( 'Location:admin.php?dir=DB' ) ;
 ?>
