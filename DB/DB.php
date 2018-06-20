<?php 
	//get html url and word to highlight
	$url = $_GET['html'].'.html';
	$words=false;
	if (!$url) echo 'No document was found';
	$string = $_GET['word'];		
	
	$str = strtok($string, ',');
	while ($str !== FALSE)
	{
		$words[] = $str;
		$str = strtok(",");
	}
	
	//get html content
	$dom = new DOMDocument();
	libxml_use_internal_errors(true); //error handeling
	$dom->loadHTMLFile($url);
	$html = $dom->saveHTML();
	
	if ($words){
		//define words to highlight
		foreach ($words as $word){
			$search = array($word, strtoupper($word), strtolower($word), ucfirst(strtolower($word)), ucwords(strtolower($word)));
			$replace = array("<span style='background:#B0E2FF'>".$word."</span>","<span style='background:#B0E2FF'>".strtoupper($word)."</span>","<span style='background:#B0E2FF'>".strtolower($word)."</span>","<span style='background:#B0E2FF'>".ucfirst(strtolower($word))."</span>","<span style='background:#B0E2FF'>".ucwords(strtolower($word))."</span>");
			//highlight the words
			$html = str_replace($search, $replace, $html);
		}
	}
	
	//print the url content
	echo $html;
?>