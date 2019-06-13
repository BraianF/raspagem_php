<?php
	
	header('Content-Type: application/json');
	
	isset($_POST['idMunicipio']) ? $idMunicipio = $_POST['idMunicipio'] : $idMunicipio = "0";
	$locais = curl_init();
	curl_setopt($locais, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($locais, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($locais, CURLOPT_URL, 'https://balneabilidade.ima.sc.gov.br/local/getLocaisByMunicipio');
	curl_setopt($locais, CURLOPT_POST, 1);
	curl_setopt($locais, CURLOPT_POSTFIELDS, "municipioID=".$idMunicipio);
	
	$result = curl_exec($locais);
	curl_close($locais);
	
	echo $result;
	
	