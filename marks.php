<?php
error_reporting(E_ALL ^ E_NOTICE);


function getMarks($file) {
	$content = file_get_contents($file);
	$json = $content;
	$json = substr($json,strpos($json,"catEntry"),strlen($json));
	$bits = explode("\n",$json);
	$colors = getMarksArray($bits[0]);
	$stock = getMarksArray($bits[1]);
	$prices = getMarksArray($bits[2]);
	$outstock = getMarksArray($bits[3]);
	$masterCode;
	foreach($colors as $size => $code) {
		$parts = explode("_",$size);
		if ($masterCode == "") {
			$masterCode = $parts[0];
		}
		$lcolor = $parts[1];
		$lsize = $parts[2];
		$key = $masterCode . "_" . $lcolor;
		$instock = $stock[$key][$lsize]["count"];
		$lsize = str_replace("DUMMY","",$lsize);
		$price = $prices[$masterCode . "_" . $lcolor];
		foreach ($price as $key => $value) {
			$info[$masterCode]["colours"][$lcolor]["price"][$key] = $value;
		}
		$info[$masterCode]["colours"][$lcolor]["stock"][$lsize] = $instock;
	}

	$meta = explode("<meta property",$content);
	for ($i=1;$i<6;$i++) {
		$bit = getMarksMeta($meta[$i]);
		foreach ($bit as $title => $value) {
			$info[$masterCode][$title] = $value; 
		}
	}	

	return $info;
}

function getMarksMeta($string) {
	$prefix = explode("og:",$string);
	$prefix = substr($prefix[1],0,strpos($prefix[1],'"'));
	$content = explode('content="',$string);
	$content = substr($content[1],0,strpos($content[1],'"'));
	$array[$prefix] = $content;
	return $array;
}

function getMarksArray($string) {
	$string = substr($string,strpos($string,"{"),strlen($string));
	$string = trim($string);
	$string = substr($string,0,strlen($string)-1);
	$array = json_decode($string,true);
	return $array;
}

#parsems("http://www.marksandspencer.com/easy-care-soft-touch-geometric-print-shirt/p/p22332090");
#parsems("http://www.marksandspencer.com/cashmilon-open-front-cardigan/p/p22335242");
?>
