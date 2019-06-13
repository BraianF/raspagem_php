<?php
	
	//Verifica se os dados foram passados por POST e atribui às variáveis para depois adicioná-las às opções
	isset($_POST['idMunicipio']) ? $idMunicipio = $_POST['idMunicipio'] : die("Município não passado por parâmetro");
	isset($_POST['idBalneario']) ? $idBalneario = $_POST['idBalneario'] : die("Município não passado por parâmetro");
	isset($_POST['ano']) ? $ano = $_POST['ano'] : die("Município não passado por parâmetro");
	
	//Adiciona as opções em um array chave/valor, sendo chave o nome do parâmetro passado por POST
	$opcoesPOST = array('municipioID' => $idMunicipio, 'localID' => $idBalneario, 'ano' => $ano);
	
	//Inicializa uma nova sessão e retorna um identificador cURL para uso com as funções curl_setopt (),
	//curl_exec () e curl_close ().
	$pesquisa = curl_init();
	
	//Define uma opção no manipulador de sessão cURL fornecido.
	//curl_setopt ( resource $ch , mixed $option , mixed $value )
	
	//O URL para buscar. Isso também pode ser definido ao inicializar uma sessão com curl_init ().
	curl_setopt($pesquisa, CURLOPT_URL,"https://balneabilidade.ima.sc.gov.br/relatorio/historico");
	
	//Modifica a opção de fazer a requisição através de POST para verdadeira
	curl_setopt($pesquisa, CURLOPT_POST, TRUE);
	
	//Adiciona os dados passados em uma operação POST.
	//Pode ser passado em uma string completa ou por um array com o nome do campo como chave e o dado como valor
	curl_setopt($pesquisa, CURLOPT_POSTFIELDS, $opcoesPOST);
	/*
	curl_setopt(
		$pesquisa,
		CURLOPT_POSTFIELDS,
		"municipioID=".$idMunicipio."&localID=".$idBalneario."&ano=".$ano."&redirect=true"
	);
	*/
	
	//TRUE para retornar a transferência como string do valor de
	//retorno de curl_exec() ao invés de mostrar diretamente
	curl_setopt($pesquisa, CURLOPT_RETURNTRANSFER, TRUE);
	
	//Executa a requisição
	$respostaDoServidor = curl_exec($pesquisa);
	
	//Fecha uma sessão cURL e libera todos os recursos. O controlador cURL, pesquisa, também é deletado.
	curl_close ($pesquisa);

	//Mostra o html carregado (para debug)
	//echo $server_output;
	
	//Cria um documento DOM para trabalhar nele. DOMDocument representa um documento HTML ou XML
	$documento = new DOMDocument();
	
	//Verifica se há algum conteúdo na requisição
	if (!empty($respostaDoServidor)){
		//Carrega o HTML a partir de uma string
		$documento->loadHTML($respostaDoServidor);
		
		//Transforma o documento em DOMXpath para realizar queries
		$documentoXPath = new DOMXPath($documento);
		
		//Separa as tabela dos titulos e dos dados para trabalhar separadamente
		//Na tabela dos títulos também vem o cabeçalho da página que é apresentada. Esta parte é tratada depois
		$documentoTabelasTitulo = $documentoXPath->query('//table[@class="table"]');
		$documentoTabelasDados = $documentoXPath->query('//table[@class="table table-print"]');
		
		//Recupera só o thead das tabelas de dados
		$theadDados = $documentoXPath->query(
			'//thead',
			$documentoTabelasDados[0]
		)->item(0)->childNodes->item(0);
		
		//Cria arrays para armazenar os dados que serão adicionados ao resultado
		$labelsTabelasTitulos = array();
		$headerTabelasDados = array();
		$dadosCorpoTabelas = array();
		
		//Verifica se há tabelas recuperadas
		if ($documentoTabelasDados->length > 0){
			//Percorre as tabelas de título para recuperar os campos município, balneário, ponto de coleta e localização
			for ($i = 1; $i < $documentoTabelasTitulo->length; $i++){
				
				//Recupera o texto dentro das <label>, onde estão as informações e remove espaços em branco
				$municipio = $documentoTabelasTitulo->item($i)->childNodes->item(0)->childNodes->item(0)->nodeValue;
				$municipio = trim($municipio);
				
				$balneario = $documentoTabelasTitulo->item($i)->childNodes->item(0)->childNodes->item(2)->nodeValue;
				$balneario = trim($balneario);
				
				$pontoDeColeta = $documentoTabelasTitulo->item($i)->childNodes->item(1)->childNodes->item(0)->nodeValue;
				$pontoDeColeta = trim($pontoDeColeta);
				
				$localizacao = $documentoTabelasTitulo->item($i)->childNodes->item(1)->childNodes->item(2)->nodeValue;
				$localizacao = trim($localizacao);
				
				//Adiciona um array dentro do array contendo as informações.
				//Array de 2 dimensões
				$labelsTabelasTitulos[] = array($municipio, $balneario, $pontoDeColeta, $localizacao);
			}
			
			//Recupera os dados de cada ponto de coleta e os põe em um array
			//O array é composto pela estrutura: array [indice da tabela][índice da linha da tabela][colunas da linha]
			for ($i =0; $i < $documentoTabelasDados->length; $i++){
				
				//lastchild é o tbody da table
				$corpoTabela = $documentoTabelasDados->item($i)->lastChild;
				
				//Insere o índice das tabelas encontradas (os resultados podem haver várias tabelas)
				$dadosCorpoTabelas[] = [$i];
				
				//Transforma o primeiro índice em array, para inserir as linhas de resultado
				$dadosCorpoTabelas[$i] = array();
				
				//Percorre o tbody
				for ($j = 0; $j < $corpoTabela->childNodes->length; $j++){
					//Junta todas as linhas do corpo da tabela em um array
					$linhasCorpoTabela = $corpoTabela->childNodes->item($j);
					
					//Insere o índice da segunda dimensão do array
					$dadosCorpoTabelas [$i][] = [$j];
					//Transforma o segundo índice em array, para inserir os dados de cada campo
					$dadosCorpoTabelas[$i][$j] = array();
					
					//Percorre as linhas da tabela para recuperar os dados contidos em coluna
					for ($k = 0; $k < $linhasCorpoTabela->childNodes->length; $k++){
						//Por algum motivo, aparecem 18 campos (um com dados e outro em branco)
						//Os dados estão apenas em campos pares
						if (!($k % 2)){
							//Adiciona os dados à linha pertencente
							$dadosCorpoTabelas[$i][$j][] =  $linhasCorpoTabela->childNodes->item($k)->nodeValue;
						}
					}
				}
			}
			
			//Põe os dados do cabeçalho da tabela de dados em um array
			for ($i = 0; $i < $theadDados->childNodes->length; $i++){
				//Por algum motivo, aparecem 18 campos (um com dados e outro em branco)
				//Os dados estão apenas em campos pares
				if (!($i % 2)){
					$headerTabelasDados[] = $theadDados->childNodes->item($i)->nodeValue;
				}
				
			}
			
			$todosOsDados [] = $labelsTabelasTitulos;
			$todosOsDados [] = $headerTabelasDados;
			$todosOsDados [] = $dadosCorpoTabelas;
//			return $labelsTabelasTitulos, $headerTabelasDados, $dadosCorpoTabelas;
			print(json_encode($todosOsDados ));
//			print_r($todosOsDados);
			
		}
	}