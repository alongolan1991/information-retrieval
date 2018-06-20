<?php

	error_reporting(0);

	// Checks if a database file exists.
	function existsDB(){
		if (file_exists("wordsDB.json")){
			return true;
		}
		return false;
	} // existsDB

	// Loads database from file.
	function loadDB(){
		$json_db = file_get_contents("wordsDB.json");
		$json_db = str_replace("null", "\"null\"", $json_db);
		return json_decode($json_db, true);
	} // loadDB

	// Saves database to file.
	function saveDB($db){
		ksort($db['METADATA']);  // sort the metadata tag in json. mainly if the word doesnt exist.
		$json_db = json_encode($db);
		file_put_contents("wordsDB.json", $json_db);
	} // saveDB

	// Initializes database.
	function initDB(){

		// Set "Stop List" words.
		$stopList = array('a', 'able', 'about', 'across', 'after', 'all', 'almost', 'also', 'am', 'among', 'an', 'and', 'any', 'are', 'as', 'at',
					  'be', 'because', 'been', 'but', 'by',
					  'can', 'cannot', 'could',
					  'dear', 'did', 'do', 'does',
					  'either', 'else', 'ever', 'every',
					  'for', 'from',
					  'get', 'got',
					  'had', 'has', 'have', 'he', 'her', 'hers', 'him', 'his', 'how', 'however',
					  'i', 'if', 'in', 'into', 'is', 'it', 'its',
					  'just',
					  'least', 'let', 'like', 'likely',
					  'may', 'me', 'might', 'most', 'must', 'my',
					  'neither', 'no', 'nor', 'not',
					  'of', 'off', 'often', 'on', 'only', 'or', 'other', 'our', 'own',
					  'rather',
					  'said', 'say', 'says', 'she', 'should', 'since', 'so', 'some',
					  'than', 'that', 'the', 'their', 'them', 'then', 'there', 'these', 'they', 'this', 'tis', 'to', 'too', 'twas',
					  'us',
					  'wants', 'was', 'we', 'were', 'what', 'when', 'where', 'which', 'while', 'who', 'whom', 'why', 'will', 'with', 'would',
					  'yet', 'you', 'your');

		// If DB exists - Load it. If not, create a new DB.
		if (existsDB()){
			$db = loadDB();
		} else {
			foreach ($stopList as $stopWord){
				$db{'STOPLIST'}{$stopWord} = 1;
			}
		}

		return $db;

	} // initDB

	// Parses the html head of a document to get the meata-data.
	function parseHead(&$db, &$lines, $filename){

		$separators = " ,\n\t\"\'\\\0\r/<>;=!().:&*|@#\$%^~`?";	// Set in file separators.

		// Parse the head and get the metadata.
		foreach ($lines as $line_num => $line){

			$word = strtolower(strtok($line, $separators));		// Get the first word of each line.
			if ($word == 'head'){								// If the head ends, finish head parsing.
				return;
			}

			if ($word == 'meta'){	// Meta tag was found
				$wordCount = 0;
				$name = '';
				$contentExp = false;
				while ($word){		// Parse the meta tag.

					if ($word == 'name'){							// Meta tag name found.
						$word = strtolower(strtok($separators));	// Get the name.
						if ($word == 'keywords' || $word == 'viewport' ){	// Meta containing keywords or viewport is ignored.
							$name = '';
						} else {
							$name = $word;
						}
					}

					if ($word == 'content'){						// Content field was found
						$contentExp = true;							// Following words are part of the content expression.
						$word = strtolower(strtok($separators));	// Get next word before next iteration.
						continue;
					}

					if (($name != '') && $contentExp){	// If part of the content expression, add word to DB.
						++$wordCount;
						$db{'METADATA'}{$word}{$filename}{$name}{$wordCount} = 1;
						if ($name == 'key'){
							$db{'FILES'}{$filename}{'KEY'} = strtoupper($word);
						}
					}

					$word = strtolower(strtok($separators));	// Get next word.

				}
			}

			array_shift($lines);	// Remove line after parsing it.

		}
	} // parseHead

	// Adds a document to the database
	function addDocToDB(&$db, $filename){

		$fileToRead = "upload/" . $filename;
		$lines = file($fileToRead);								// Get file content.
		$separators = " ,\n\t\"\'\\\0\r/<>;=!().:&*|@#\$%^~`?";	// Set in file separators.
		$db{'FILES'}{$filename}{'ACTIVE'} = 1;					// Set the file as active.

		foreach ($lines as $line_num => $line){				// Look for the HTML head.
			$word = strtolower(strtok($line, $separators));	// Get the first word of each line.
			if ($word == 'head'){							// If the head starts, go to head parsing.
				array_shift($lines);						// Remove the line with the head tag.
				parseHead($db, $lines, $filename);
				break;
			}
			array_shift($lines);	// Remove line after parsing it.
		}

		saveDB($db);

	} // addDocToDB

	// inactive doc
	function inactiveDocFromDB(&$db, $filename){
		if($db{'FILES'}{$filename}{'ACTIVE'} == 0)	// Set the file as inactive.
			$db{'FILES'}{$filename}{'ACTIVE'} = 1;
		else{
			$db{'FILES'}{$filename}{'ACTIVE'} = 0;
		}
	} // inactive doc


	// Removes a document from the database by setting it as inactive but without deleting its content.
	function removeDocFromDB(&$db, $filename){
		$db{'FILES'}{$filename}{'ACTIVE'} = 0;	// Set the file as inactive.
	} // removeDocFromDB

	// Deletes a document from the database by removing all its content.
	function deleteDocFromDB(&$db, $filename){

		if (!isset($db{'FILES'}{$filename})){	// Nothing to delete if file doesn't exist.
			return;
		}

		$words = $words = array_keys($db{'METADATA'});
		foreach ($words as $word){						// For each word in the metadata, remove all appearances in the file from the database.
			unset($db{'METADATA'}{$word}{$filename});
			if ($db{'METADATA'}{$word} == null){		// If this was the only document the word appeared in, delete the word as well.
				unset($db{'METADATA'}{$word});
			}
		}


		unset($db{'FILES'}{$filename});	// Remove the file from the files list.
		saveDB($db);
	} // deleteDocFromDB

	// Updates the content of the file in the database.
	function updateDocInDB(&$db, $filename){
		deleteDocFromDB($db, $filename);	// Delete old content.
		addDocToDB($db, $filename);			// Insert new content.
	} // editDocInDB

	// Gets two lists and returns a list with all the elents that appear in both lists.
	function resultsAND($resultsList1, $resultsList2){

		$result = array();
		foreach (array_keys($resultsList2) as $resultElement){			// Get each element from the second list and save it if it appears in the first list as well.
			if (in_array($resultElement, array_keys($resultsList1))){
				$result{$resultElement} = $resultsList1{$resultElement} + $resultsList2{$resultElement};
			}
		}

		return $result;

	} // resultsAND

	// Gets two lists and returns a list with all the elents that appear in either one or both, lists.
	function resultsOR($resultsList1, $resultsList2){

		foreach (array_keys($resultsList1) as $resultElement){			// Save all elements from the first list.
			$result{$resultElement} = $resultsList1{$resultElement};
		}
		foreach (array_keys($resultsList2) as $resultElement){			// Save each element in the second list as well.
			$result{$resultElement} = $resultsList2{$resultElement};

		}

		return $result;

	} // resultsOR

	// Gets a list and returns all documents in the database that are not in the list.
	function resultsNOT($db, $resultsList){

		$totalList = array();
		foreach (array_keys($db{'FILES'}) as $file){		// Create a list of all the documents in the database.
			if ($db{'FILES'}{$file}{'ACTIVE'}){
				$totalList{$db{'FILES'}{$file}{'KEY'}} = 1;
			}
		}

		$result = array();
		foreach (array_keys($totalList) as $resultElement){	// Save each element in the total list that doesn't appear in the given list.
			if (!in_array($resultElement, array_keys($resultsList))){
				$result{$resultElement} = $totalList{$resultElement};
			}
		}

		return $result;

	} // resultsNOT

	// Gets a string and returns the first token it finds.
	// The tokens are:
	// '(' - Open parentheses.
	// ')' - Close parentheses.
	// '&' - AND sign.
	// '|' - OR sign.
	// '!' - NOT sign.
	// "<STRING>" - A string encased in double quotes.
	// <STRING> - A string without double quotes (Will be encased in asterisks).
	function getToken(&$str){

		if (($str[0] == " ") || ($str[0] == "\t")){	// Ignore white-space characters.
			$str = substr($str, 1);
			return getToken($str);
		}

		if ($str[0] == "("){		// Check for opening parentheses.
			$str = substr($str, 1);
			return "(";
		}

		if ($str[0] == ")"){		// Check for closing parentheses.
			$str = substr($str, 1);
			return ")";
		}

		if ($str[0] == "&"){		// Check for AND sign.
			$str = substr($str, 1);
			return "&";
		}

		if ($str[0] == "|"){		// Check for OR sign.
			$str = substr($str, 1);
			return "|";
		}

		if ($str[0] == "!"){		// Check for NOT sign.
			$str = substr($str, 1);
			return "!";
		}

		if ($str[0] == "\""){		// Check for double quotes.
			$token = "\"";			// Get the double quotes.
			$str = substr($str, 1);
			while (($str != "") && ($str[0] != "\"")){	// Get all characters until the next double quotes.
				$token .= $str[0];
				$str = substr($str, 1);
			}
			$token .= "\"";			// Close the double quotes.
			$str = substr($str, 1);
			return $token;
		}

		$token = "*";	// No special characters left. Get all characters until the next special character.
		while (($str != "") && ($str[0] != "(") && ($str[0] != ")") && ($str[0] != "\"") && ($str[0] != "&") && ($str[0] != "|") && ($str[0] != "!")){
			$token .= $str[0];
			$str = substr($str, 1);
		}
		$token .= "*";
		return $token;

	} // getToken

	// Gets a string and returns an array of tokens that match that string.
	function parseQueryStr($queryStr){

		$parsedQueryStr = array();
		while ($queryStr != ""){
				$token = getToken($queryStr);
				array_push($parsedQueryStr, $token);
		}

		return $parsedQueryStr;

	} // parseQueryStr

	// Gets a parsed query array and finds in the database all the tokens that contain search words.
	function searchTokens($db, $parsedQuery){

		$afterSearchQuery = array();
		foreach ($parsedQuery as $token){
			$result = $token;
			if ($token[0] == "*"){	// Regular strings are searched as single words.
				$token = str_replace("*", "", $token);
				$result = find($db, $token);
			}
			if ($token[0] == "\""){	// Strings enacased in double quotes are searched as a phrase and stop-list words aren't filtered out.
				$token = str_replace("\"", "", $token);
				$result = findExact($db, $token);
			}
			array_push($afterSearchQuery, $result);
		}

		return $afterSearchQuery;

	} // searchTokens

	// Gets the content of one parentheses and returns a list with the search results of that parentheses.
	function calcOneParentheses($db, $content){

		$count = count($content);
		if (($count == 1) && ($content[0] != "&") && ($content[0] != "|") && ($content[0] != "!")){	// If there's only one token, no further calculation is needed.
			return $content[0];
		}
		if (($count == 2) && ($content[0] == "!")){	// Two tokens match only the NOT clause.
			return resultsNOT($db, $content[1]);
		}
		if ($count == 3){	// Calculate AND or OR operators.
			if ($content[1] == "&"){
				return resultsAND($content[0], $content[2]);
			}
			if ($content[1] == "|"){
				return resultsOR($content[0], $content[2]);
			}
		}

		// If any other case, the query is illegal.
		echo "<script type=\"text/javascript\">";
		echo "	window.alert(\"Illegal query. Make sure all parentheses and operators are placed correctly.\")";
		echo "</script>";
		return null;

	} // calcOneParentheses

	// Gets a parsed query and calculates the result.
	function calcQuery($db, $queryArray){

		$calcStack = array();
		while (!empty($queryArray)){	// As long as there are tokens, add them to the stack.
			$token = array_shift($queryArray);
			if (($token == ")")){		// Whenever a closing bracket is encountered, get all the content until the opening bracket.
				$stackToken = array_pop($calcStack);
				$parenthesesStack = array();
				while ($stackToken != "("){
					array_unshift($parenthesesStack, $stackToken);
					$stackToken = array_pop($calcStack);
					if (empty($stackToken)){
						echo "<script type=\"text/javascript\">";
						echo "	window.alert(\"Parentheses mismatch.\")";
						echo "</script>";
						return null;
					}
				}
				$calcResult = calcOneParentheses($db, $parenthesesStack);	// Once all the parentheses' content is collected, calcute its value.
				array_push($calcStack, $calcResult);						// Replaces the parentheses with its result.
			} else {
				array_push($calcStack, $token);
			}
		}
		$calcResult = calcOneParentheses($db, $calcStack);	// Calculate the last expression once no parentheses remain.

		return $calcResult;

	} // calcQuery

	// Finds a set of words in the database and returns a list of containing documents.
	function find($db, $queryStr){

		$words = explode(' ', $queryStr);
		$documents = null;
		foreach (array_unique($words) as $word){	// Search for each word separately and get all the documents it appears in.
			if ($db{'STOPLIST'}{$word} == 1){		// Ignore words in the stop-list.
				continue;
			}

			$appearsInDocuments = array_keys($db{'METADATA'}{$word});
			foreach ($appearsInDocuments as $document){		// Check if the word appears in the metadata of the document.
				if (!$db{'FILES'}{$document}{'ACTIVE'}){	// If the document is inactive, go to the next document.
					continue;
				}
				$key = $db{'FILES'}{$document}{'KEY'};
				++$documents{$key};		// Add this document to the results' list.
			}

		}

		return $documents;

	} // find

	// Finds a set of words in the database as a phrase and returns a list of containing documents.
	function findExact($db, $queryStr){

		$words = explode(' ', $queryStr);
		$documents = null;
		$firstWord = array_shift($words);
		$numWordsLeft = count($words);
		$appearsInDocuments = array_keys($db{'METADATA'}{$firstWord});	// Find all the documents containing the first word.
		foreach ($appearsInDocuments as $document){					// Look for the phrase in each document.
			if (!$db{'FILES'}{$document}{'ACTIVE'}){				// Ignore inactive documents.
				continue;
			}

			$names = array_keys($db{'METADATA'}{$firstWord}{$document});
			foreach ($names as $name){		// Look for the phrase in every field of the metadata in each document.
				$firstWordLocations = array_keys($db{'METADATA'}{$firstWord}{$document}{$name});
				foreach ($firstWordLocations as $location){		// Wherever the first word is found, go through the following words and check if they match the phrase.
					for ($i = 0; $i <= $numWordsLeft; ++$i){
						$word = $words[$i];
						$wordLocation = $location + $i + 1;
						if ($db{'METADATA'}{$word}{$document}{$name}{$wordLocation} != 1){
							break;
						}
					}
					if ($i == $numWordsLeft){	// If the phrase was found, add the document to the results' list. Metadata phrases have higher value than regular phrases.
						$key = $db{'FILES'}{$document}{'KEY'};
						++$documents{$key};
					}
				}
			}
		}

		// arsort($documents);	// Sort by relevance.

		return $documents;

	} // findExact

	// Gets a query string and returns a json containing the search results.
	function searchDB($db, $queryStr){

		$queryStr = strtolower($queryStr);
		$parsedQueryStr = parseQueryStr($queryStr);				// Parse the string into tokens.
		$afterSearchQuery = searchTokens($db, $parsedQueryStr);	// Search all relevant tokens.
		$afterCalcQuery = calcQuery($db, $afterSearchQuery);	// Calculate all parentheses and logical operators.

		if (isset($afterCalcQuery) && $afterCalcQuery != null){	// If there are any results, set the query as found.
			$results{'found'} = "yes";
		} else {
			$results{'found'} = "no";
		}

		$words = array();
		foreach ($parsedQueryStr as $token){	// Create a list of searched tokens (Single words and phrases).
			if ($token[0] == "*"){
				$token = str_replace("*", "", $token);
				foreach (explode(' ', $token) as $word){
					array_push($words, $word);
				}
			}
			if ($token[0] == "\""){
				$token = str_replace("\"", "", $token);
				array_push($words, $token);
			}
		}
		foreach ($words as $word){
			$results{'words'}{$word} = 1;
		}

		$keys = array_keys($afterCalcQuery);	// Create a list of documents.
		foreach ($keys as $key){
			$results{'documents'}{$key} = 1;
		}

		return $results;

	} // searchDB
?>
