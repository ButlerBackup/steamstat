<?php 
$steamStatsUrl  = "http://store.steampowered.com/stats/";

function indent($json) {
	$result      = '';
	$pos         = 0;
	$strLen      = strlen($json);
	$indentStr   = '  ';
	$newLine     = "\n";
	$prevChar    = '';
	$outOfQuotes = true;

	for ($i=0; $i<=$strLen; $i++) {
		$char = substr($json, $i, 1);
		if ($char == '"' && $prevChar != '\\') {
			$outOfQuotes = !$outOfQuotes;
		} else if(($char == '}' || $char == ']') && $outOfQuotes) {
			$result .= $newLine;
			$pos --;
			for ($j=0; $j<$pos; $j++) {
				$result .= $indentStr;
			}
		}
		$result .= $char;
		if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
			$result .= $newLine;
			if ($char == '{' || $char == '[') {
				$pos ++;
			}

			for ($j = 0; $j < $pos; $j++) {
				$result .= $indentStr;
			}
		}
		$prevChar = $char;
	}
	return $result;
}

function nextDay() {
	return strtotime("+1 day");
}

function getData($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}

$currentYear = date('Y');
$currentMonth = date('m');

if (!file_exists('stats/' . $currentYear . '/' . $currentMonth)) {
	mkdir('stats/' . $currentYear . '/' . $currentMonth, 0777, true);
}

$steamData = preg_replace( "/\s+/", " ", getData($steamStatsUrl));
preg_match_all('/<span class="currentServers">([^<]*?)<\/span> <\/td> <td align="right"> <span class="currentServers">([^<]*?)<\/span> <\/td> <td width="20">&nbsp;<\/td> <td> <a class="gameLink" href="([^"]*?)">([^<]*?)<\/a> <\/td> <\/tr>/', $steamData, $matches);

$parsedData = array();
for ($i = 0; $i < count($matches[1]); $i++) {
	$parsedData[$i]['Name'] = $matches[4][$i];
	$parsedData[$i]['Url'] = $matches[3][$i];
	$parsedData[$i]['Current'] = str_replace(',', '', $matches[1][$i]);
	$parsedData[$i]['Peak'] = str_replace(',', '', $matches[2][$i]);
}

$finalData = array();
$finalData['Time'] = time();
$finalData['Games'] = $parsedData;

$fileName = date('Y-m-d');
$file = 'stats/' . $currentYear . '/' . $currentMonth . '/' . $fileName . '.json';
$handle = fopen($file, 'a+') or die('Cannot open file:  '.$file); //place the file pointer at the end of the file. 
fwrite($handle, indent(json_encode($finalData)));
fclose($handle);