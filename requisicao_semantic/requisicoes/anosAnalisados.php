<?php
	header('Content-Type: application/json');
	
	$anos = curl_init();
	curl_setopt($anos, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($anos, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($anos, CURLOPT_URL, 'https://balneabilidade.ima.sc.gov.br/registro/anosAnalisados');
	curl_setopt($anos, CURLOPT_POST, 1);
	
	$result = curl_exec($anos);
	curl_close($anos);
	
	echo $result;
	
	//		$listaMunicipios = json_decode($result);
	//		echo $listaMunicipios[0]->CODIGO;
	//		echo $listaMunicipios[0]->DESCRICAO;
	//		var_dump($listaMunicipios);
	//		foreach ($listaMunicipios as $municipio ){
	//			echo "<option value=".$municipio->CODIGO.">".$municipio->DESCRICAO."</option>";
	//		}
