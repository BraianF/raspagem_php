<?php
	//muda o cabecalho HTTP indicando o conteudo do arquivo, que nao é HTML, mas sim JSON
	header('Content-type: application/json');
	//Verifica se os dados foram passados por POST e atribui às variáveis para depois adicioná-las às opções
	isset($_POST['idMunicipio']) ? $idMunicipio = $_POST['idMunicipio'] : die("Município não passado por parâmetro");
	isset($_POST['idBalneario']) ? $idBalneario = $_POST['idBalneario'] : die("Município não passado por parâmetro");
	isset($_POST['ano']) ? $ano = $_POST['ano'] : die("Município não passado por parâmetro");
	
//	$idMunicipio = 12;
//	$idBalneario = 0;
//	$ano = 2019;
	
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
	
	//Executa a requisição - resposta é o HTML inteiro como texto
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
			$dados = [];
			//SPERONI
			foreach ($documentoTabelasTitulo as $key=>$tabela) {
				if ($key!=0){
					$labels = $tabela->getElementsByTagName('label'); //lista dos nós do tipo label
					$titulos = [];
					foreach ($labels as $label) { //um label por vez
						$partesTitulo = explode(': ', $label->nodeValue); //quebro o valor no :
						$indice = str_replace(' ', '_', $partesTitulo[0]); //substituindo o espaço por underline (na parte que será o indice)
						$titulos[$indice] = $partesTitulo[1]; //cria a posicao chamada $indice com valor da segunda parte
					}
					$dados[]['titulos'] = $titulos;
				}
			}
			foreach ($documentoTabelasDados as $key => $tabelasDados) {
				$trs = $tabelasDados->getElementsByTagName('tr');
				$coletas = [];
				foreach ($trs as $linha => $tr) {
					if ($linha!=0){
						$tds = $tr->getElementsByTagName('td');
						$coleta = [];
						foreach ($tds as $td) {
							$class = $td->getAttribute('class');
							$coleta[$class] = $td->nodeValue;
						}
						$coletas[] = $coleta;
					}
				}
				$dados[$key]['coletas'] = $coletas;
			}
			echo json_encode($dados);
		}
	}