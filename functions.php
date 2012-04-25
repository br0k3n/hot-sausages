<?php
/*
Stuff
*/

function exceptrText($text, $chars) { 
    $text = $text." "; 
    $text = substr($text,0,$chars); 
    $text = substr($text,0,strrpos($text,' ')); 
    $text = $text."...";
    return $text; 
}

/*
Convert date Ymj
*/

function convertDateYMJ($date) {
	return date("Y/m/j", strtotime($date));
}

/*
Convert date jmY
*/

function convertDateJMY($date) {
	return date("j-m-Y", strtotime($date));
}

/*
debug array
*/

function view_array($array) {
	echo('<pre>');
	print_r($array);
	echo('</pre>');
}

?>