<?php
	include "wordDBhandler.php";
	//Gets all the html files in the directory
	$directory = $_GET['dir']."/*.{html}";
	$files = glob($directory, GLOB_BRACE);

	$db = initDB();

	if(is_writable('wordsDB.json')) {
		unlink('wordsDB.json');
		$db = initDB();
	}
	//Update files DB
	foreach($files as $file) {
		$meta = get_meta_tags($file);
		$file = $meta['key'].'.html';
		rename("DB/".$file, "upload/".$file);
		updateDocInDB($db, $file);
		rename("upload/".$file, "DB/".$file);
	}
	//end

	header( 'Location:admin.php?dir=DB' ) ;
?>
