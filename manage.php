<?php

	error_reporting(E_ALL ^ E_WARNING);
	
	$url = $_POST["url"];

	$config = file_get_contents("config.json");
	$config = json_decode($config,true);
/*	
	$code = $_POST["code"];
	if ($code != $config["code"])	{
		http_response_code(403);
		echo "403 Forbidden";
		exit();
	}
*/
	$exists = false;
	$sizes = $config["sizes"];
	$products = $config["products"];
	for($i=0;$i<count($products);$i++) {
		$product = $products[$i];
		$data = getData($product);
		if ($data["url"] == $url) {
			$exists = true;
		}
	}

	if (!$exists) {
		$product = "";
		$product["url"] = str_replace("\\","",$url);
		$product["store"] = "marks";
		$product["watchPrice"] = "";
		$config["products"][] = $product;
		$config_out = json_encode($config,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		file_put_contents("config.json",$config_out);
	}
	
	function getData($product) {
		$url = $product["url"];
		$store = $product["store"];
		if ($store == "marks") { 
			require_once('marks.php');
			$data = getMarks($product["url"]);
		}
		return $data;
	}

?>
