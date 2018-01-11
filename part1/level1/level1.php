<?php
	$data = file_get_contents("data.json");
	$jsdata = json_decode($data, true);

	$providers = array();
	foreach($jsdata['providers'] as $valor) {
		$providers[$valor['id']] = $valor['price_per_kwh'];
	}

	$bills['bills'] = array();
	foreach($jsdata['users'] as $key=>$valor){
		$bill = array ('id' => $key + 1, 'price' => $valor['yearly_consumption']*$providers[$valor['provider_id']], 'user_id' => $valor['id']);
		$bills['bills'][] = $bill;
	}
	echo json_encode($bills, JSON_PRETTY_PRINT);
?>