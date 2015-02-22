<?php

require_once 'config.php';
require_once 'Twitter.php';

function getTopID($response){
	for($i=0;$i<15;$i++){
		if(strpos($response[$i]->text,"@lanevok")===0&&
			strstr($response[$i]->text,"のポスト数：")&&
			strstr($response[$i]->text,"うちRT")){
			continue;
		}
		else{
			return $response[$i]->id_str;
		}
	}
	return $response[0]->id_str;
}

function getHeadText($response){
	return $response[0]->text;
}

function getMain($response){
	$text = "";
	for($i=0;$i<15;$i++){
		$text .= date('Y-m-d H:i:s', strtotime((string)$response[$i]->created_at))."\n\n";
		$text .= $response[$i]->text."\n\n----------\n";
	}
	return $text;
}

function readLast(){
	return file_get_contents("last.txt");
}

function readPower(){
	return file_get_contents("power.txt");
}

function writeLast($text){
	file_put_contents("last.txt",$text);
}

function doMail($head,$text){
	mb_language("Ja") ;
	mb_internal_encoding("UTF-8") ;
	$mailto="sample@lanevok.com";
	$subject=mb_strimwidth($head, 0, 36, '…');
	$content=$text;
	$mailfrom="From:" .mb_encode_mimeheader("Twitter Update Mail") ."<sample@lanevok.com>";
	mb_send_mail($mailto,$subject,$content,$mailfrom);
}

function getDiff($src, $dst){
	return strcmp($src, $dst);
}

if(readPower()!=1){
	print_r("停止中");
	exit(0);
}
$api = new Twitter();
$response = $api->userTL("lanevok");
if(getDiff(getTopID($response),readLast())>0){
	$top = getHeadText($response);
	$main = getMain($response);
	doMail($top, $main);
	writeLast(getTopID($response));
}
