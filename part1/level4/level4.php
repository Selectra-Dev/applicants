<?php
	$data = file_get_contents("data.json");
	$jsdata = json_decode($data, true);

	$usuarios = array();
	foreach($jsdata['users'] as $valor) {
		$usuarios[$valor['id']] = array('yearly_consumption'=>$valor['yearly_consumption'], 'contratos'=>array());
	}

	foreach($jsdata['contracts'] as $valor) {
		$usuarios[$valor['user_id']]['contratos'][] = $valor;
	}

	$providers = array();
	foreach($jsdata['providers'] as $valor) {
		$providers[$valor['id']] = $valor['price_per_kwh'];
	}

	$ids = 1;
	$bills['bills'] = array();
	foreach($usuarios as $key=>$valor){
		$precio = 0.0;
		$ifee = 0.0;
		$pfee = 0.0;
		$sfee = 0.0;
		foreach($valor['contratos'] as $contrato) {
			$descuento = 0.9;
			if ($contrato['contract_length'] > 1 and $contrato['contract_length'] <= 3) {
				$descuento = 0.8;
			}
			elseif ($contrato['contract_length'] > 3){
				$descuento = 0.75;
			}
			$consumo = $contrato['contract_length']*$valor['yearly_consumption'];
			$p = $contrato['contract_length']*$valor['yearly_consumption']*$providers[$contrato['provider_id']]*$descuento;
			if ($contrato['green']) {
				$p -= $consumo*0.05;
			}
			$ie = 0.05*$contrato['contract_length']*365.0;
			$pr = $p - $ie;
			$s = $pr*0.125;

			$precio += $p;
			$ifee += $ie;
			$pfee += $pr;
			$sfee += $s;
		}
		
		$bill = array('id' => $ids, 'price' => $precio, 'user_id' => $key);
		$bill['commission'] = array('insurance_fee' => round($ifee, 2), 'provider_fee' => round($pfee,2), 'selectra_fee' => round($sfee, 2));
		$bills['bills'][] = $bill;
		$ids++;
	}
	echo json_encode($bills, JSON_PRETTY_PRINT);
?>