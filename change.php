<?php

function readPower(){
	return file_get_contents("power.txt");
}

function writePower($text){
	file_put_contents("power.txt",$text);
}

writePower(readPower()*-1);

if(readPower()==1){
	print_r("稼働");
}
else{
	print_r("停止");
}