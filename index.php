<?php

	error_reporting(E_ALL ^ E_WARNING);

	$config = file_get_contents("config.json");
	$config = json_decode($config,true);
/*	
	$code = $_GET["code"];
	if ($code != $config["code"])	{
		http_response_code(403);
		echo "403 Forbidden";
		exit();
	}
*/
	include('_includes/header.html');

	$sizes = $config["sizes"];
	$products = $config["products"];
	for($i=0;$i<count($products);$i++) {
		$product = $products[$i];
		$data = getData($product);
		drawProduct($product,$data,$sizes);
	}
	
	include('_includes/footer.html');

	function getData($product) {
		$url = $product["url"];
		$store = $product["store"];
		if ($store == "marks") { 
			require_once('marks.php');
			$data = getMarks($product["url"]);
		}
		return $data;
	}

	function drawProduct($product,$data,$sizes) {
		$watchPrice = $product["watchPrice"];
		foreach($data as $id => $values) {
			$data = $data[$id];
			$colours = $values["colours"];
		}
		$title = str_replace(" | M&S","",$data["title"]);
		echo '<h1><a href="'.$product["url"].'" target="_blank">' . $title . '</a>  ' . getPriceBlock($colours) . '</h1>';
		if ($data["image"]) {
			echo '<img id="prodImg" src="' . $data["image"] . '" alt="image"></img>';
		}
		echo getStockBlock($colours,$sizes);
		echo '<hr/>';
	}
	
function getStockBlock($colours,$sizes) {
	for($i=0;$i<count($sizes);$i++) {
		$newSize[$sizes[$i]] = true;
	}
	$sizes = $newSize;
	$block .= '<section class="stock">';
	$count = 0;
	foreach ($colours as $colour => $info) {
		$count += 1;
		if ($count > 3) {
			$block .= '</section><section class="stock">';
			$count = 1;
		}
		$block .= '<h2 style="margin-bottom: 0px;">' . $colour . ' (';
		$price = $info["price"]["price"];
		$block .= $price . ')</h2>';
		$offerText = $info["price"]["offerText"];
		if ($offerText) {
			$block .= '<b>' . $offerText . '</b><br/>';
		} else {
			$block .= '&nbsp;<br/>';
		}
		$stock = $info["stock"];
		foreach ($stock as $size => $qty) {
			if ($sizes[$size]) {
				$block .= $size . ' : ' . $qty . ' | ';
			}
		}
		$block = substr($block,0,-3);
		$block .= '<br/>';
	}
	$block .= '</section>';
	return $block;
}
function getPriceBlock($colours) {
	foreach ($colours as $colour => $info) {
		$price = $info["price"]["price"];
		$price = str_replace("&pound;","",$price);
		$offerText = $info["price"]["offerText"];
		$prices[$price] += 1;
		if ($offerText != "") {
			$offers[$offerText] += 1;
		}
	}
	ksort($prices);
	foreach ($prices as $price => $count) {
		$new[] = $price;
	}
	$prices = $new;
	if (count($prices) > 1) {
		return '<b>&pound;' . $prices[0] . ' - &pound;' . $prices[count($prices) - 1] . '</b>';
	} else { 
		return '<b>&pound;' . $prices[0] . '</b>';
	}
}
?>
