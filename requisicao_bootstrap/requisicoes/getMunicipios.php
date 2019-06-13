<?php
	header('Content-Type: application/json');
	
	$municipios = curl_init();
	curl_setopt($municipios, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($municipios, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($municipios, CURLOPT_URL, 'https://balneabilidade.ima.sc.gov.br/municipio/getMunicipios');
	curl_setopt($municipios, CURLOPT_POST, 1);
	
	$result = curl_exec($municipios);
	curl_close($municipios);
	
	echo $result;
	//		$listaMunicipios = json_decode($result);
	//		echo $listaMunicipios[0]->CODIGO;
	//		echo $listaMunicipios[0]->DESCRICAO;
	//		var_dump($listaMunicipios);
	
	
	//		foreach ($listaMunicipios as $municipio ){
	//			echo "<option value=".$municipio->CODIGO.">".$municipio->DESCRICAO."</option>";
	//		}
