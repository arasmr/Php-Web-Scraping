<?php

// include the DOM parser

include ("simple_html_dom.php");

// we set the default page number as '1'
// it is also possible to output the other pages and save as Json file
$pageNumber = $argv[1] ? $argv[1] : 1;

$url = "http://footballdatabase.com/ranking/world/".$pageNumber;

// create curl resource then set Url

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Execute the cURL session

$html = curl_exec($ch);

// Close the cURL session.

curl_close($ch);

// create a DOM parser object

$dom = new simple_html_dom();

// parse the HTML from web-page.

$dom->load($html);

// we loop inside the table body for each row of the table
foreach($dom->find('tbody tr') as $tr)
	{

	// create an empty generic php object

	$myObj = new stdClass();

	// get data from each table row and passing them in Object

	$myObj->rank = $tr->find('td.rank') [0]->innertext;
	$myObj->team = $tr->find('td.club div.limittext') [0]->innertext;
	$myObj->country = $tr->find('td.club a.sm_logo-name') [0]->innertext;

	// check value of the object

	if ($myObj->rank != null)
		{
		$rankingData[] = $myObj;
		}
	}

// created the JSON representation of the value
// also used Json_Pretty_Print to make the output much pretty and understandable

$outputObj = json_encode($rankingData, JSON_PRETTY_PRINT);

// created a Json file which has the value of wanted page in the same directory with the time stamp
$fileName = 'ranking-'.$pageNumber.'-'.time().'.json';
$fp = fopen($fileName, 'w');
fwrite($fp, $outputObj);
fclose($fp);


// printed the wanted value (Json Object)

print_r($outputObj);

?>
