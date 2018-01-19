<?php
	echo 'Welcome to the new SELECTRA tool that help you with your bills!'.PHP_EOL;
	echo 'Commands: feed *.php *.json/user $user_i/provider $provider_id/billcommissions $bill_id/mycontract $user_id/exit'.PHP_EOL;
	$aux = fopen("php://stdin","r");
	$data = array();
	$dataIni = array();
	while (true) {
		$line = fgets($aux);
		$line = substr_replace($line, "", -1);
		if ($line == 'exit') {
			exit;
		}

		$input = explode(' ', $line);
		if (count($input) == 3) {
			if ($input[0] == 'feed') {
				$data = shell_exec('php '.$input[1].' '.$input[2]);
				$dataIni = file_get_contents($input[2]);
				$jsdataIni = json_decode($dataIni, true);
				$jsdata = json_decode($data, true);
				echo 'reading database'.PHP_EOL;
				echo $dataIni.PHP_EOL;
				echo $data.PHP_EOL;
			}
		} elseif (count($input) == 2) {
			$input[1] = intval($input[1]);
			if ($input[0] == 'user') {
				if ($jsdata == null) {
					echo 'Please feed the DB'.PHP_EOL;
				} elseif ($input[1] < 0) {
					echo 'The user id must be a positive integer'.PHP_EOL;
				} else {
					$found = false;
					foreach($jsdata['bills'] as $valor) {
						if ($valor['user_id'] == $input[1]) {
							echo '---------------------'.PHP_EOL;
							echo 'User id: '.$input[1].PHP_EOL;
							echo 'Bill id: '.$valor['id'].PHP_EOL;
							echo 'Price: '.$valor['price'].PHP_EOL;
							foreach($jsdataIni['users'] as $usr) {
								if ($usr['id'] == $input[1]) {
									echo 'Yearly consumption: '.$usr['yearly_consumption'].PHP_EOL;
									break;
								}
							}
							echo '---------------------'.PHP_EOL;
							$found = true;
						}
					}
					if (!$found) {
						echo 'There is no user with this id'.PHP_EOL;
					}

				}
			} elseif ($input[0] == 'billcommission') {
				if ($jsdata == null) {
					echo 'Please feed the DB'.PHP_EOL;
				} elseif ($input[1] < 0) {
					echo 'The bill id must be a positive integer'.PHP_EOL;
				} else {
					$found = false;
					foreach($jsdata['bills'] as $valor) {
						if ($valor['id'] == $input[1]) {
							echo '---------------------'.PHP_EOL;
							echo 'Bill id: '.$input[1].PHP_EOL;
							echo 'Insurance fee: '.$valor['commission']['insurance_fee'].PHP_EOL;
							echo 'Provider fee: '.$valor['commission']['provider_fee'].PHP_EOL;
							echo 'Selectra fee: '.$valor['commission']['selectra_fee'].PHP_EOL;
							echo '---------------------'.PHP_EOL;
							$found = true;
						}
					}
					if (!$found) {
						echo 'There is no bill id with this id'.PHP_EOL;
					}
				}
			} 
			elseif ($input[0] == 'provider') {
				if ($jsdata == null) {
					echo 'Please feed the DB'.PHP_EOL;
				} elseif ($input[1] < 0) {
					echo 'The provider id must be a positive integer'.PHP_EOL;
				} else {
					$found = false;
					foreach($jsdataIni['providers'] as $valor) {
						if ($valor['id'] == $input[1]) {
							echo '---------------------'.PHP_EOL;
							echo 'Provider id: '.$valor['id'].PHP_EOL;
							echo 'Price per kwh: '.$valor['price_per_kwh'].PHP_EOL;
							echo '---------------------'.PHP_EOL;
							$found = true;
						}
					}
					if (!$found) {
						echo 'There is no provider with this id'.PHP_EOL;
					}

				}
			} elseif ($input[0] == 'mycontracts') {
				if ($jsdata == null) {
					echo 'Please feed the DB'.PHP_EOL;
				} elseif ($input[1] < 0) {
					echo 'The user id must be a positive integer'.PHP_EOL;
				} else {
					$found = false;
					foreach($jsdataIni['contracts'] as $valor) {
						if ($valor['user_id'] == $input[1]) {
							echo '---------------------'.PHP_EOL;
							echo 'User id: '.$input[1].PHP_EOL;
							echo 'Contract id: '.$valor['id'].PHP_EOL;
							echo 'Contract length: '.$valor['contract_length'].PHP_EOL;
							echo 'provider id: '.$valor['provider_id'].PHP_EOL;
							echo '---------------------'.PHP_EOL;
							$found = true;
						}
					}
					if (!$found) {
						echo 'There is no contract with this id'.PHP_EOL;
					}
				}
			} else {
				echo 'Unknown command'.PHP_EOL;
			}
		} else {
			echo 'Unknown command'.PHP_EOL;
		}
	}
?>
