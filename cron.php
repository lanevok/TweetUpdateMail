<?php

require_once 'config.php';
require_once 'Twitter.php';

function getTopID($response){
	return $response[0]->id_str;
}

function getHeadText($response){
	return $response[0]->text;
}

function getMain($response){
	$text = "";
	for($i=0;$i<10;$i++){
		$text .= $response[$i]->text."\n-----\n";
	}
	return $text;
}

function readLast(){
	return file_get_contents("last.txt");
}

function writeLast($text){
	file_put_contents("last.txt",$text);
}

function doMail($head,$text){
	mb_language("Ja") ;
	mb_internal_encoding("UTF-8") ;
	$mailto="sample@lanevok.com";
	$subject=$head;
	$content=$text;
	$mailfrom="From:" .mb_encode_mimeheader("Twitter Update Mail") ."<sample@lanevok.com>";
	mb_send_mail($mailto,$subject,$content,$mailfrom);
}

function getDiff($src, $dst){
	return strcmp($src, $dst);
}

$api = new Twitter();
$response = $api->userTL("lanevok");
if(getDiff(getTopID($response),readLast())>0){
	$top = getHeadText($response);
	$main = getMain($response);
	doMail($top, $main);
	writeLast(getTopID($response));
}
