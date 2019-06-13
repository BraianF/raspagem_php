$(document).ready(function () {
	// console.log('reativar getmunicipios, get anos e remover os dados');
	//Carrega a lista de municípios
	getMunicipios();

	//Carrega a lista de anos
	anosAnalisados();


	//Inicializa variáveis vazias para uso em funções
	let pontosDeColeta = [];
	let pontosChecked = [];

	let nomesDasCores = Object.keys(coresDoGrafico);

	//Configuração inicial do Chart. não contém dados
	let config = {
		// Tipo de gráfico
		type: 'line',

		// Os dados estão vazios. Serão adicionados depois
		data: {
			// Itens do eixo x
			labels: [],

			// Valores do eixo y
			datasets: []
		},
		// Configuration options go here
		options: {
			responsive: true,

			// Título do gráfico
			title: {
				display : true,
				text : 'Resultado da busca:'
			},
			scales: {
				xAxes:[{
					display: true,

					// Legenda do eixo x
					scaleLabel:{
						display : true,
						labelString : "Datas das coletas"
					}
				}],
				yAxes : [{
					display: true,

					// Legenda do eixo y
					scaleLabel: {
						display : true,
						labelString: ""
					},

					// Valores do eixo y
					ticks :{
						// Mostra apenas números inteiros
						callback: function(value) {if (value % 1 === 0) {return value;}},
						suggestedMax : 1
					}
				}]
			}
		}
	};

	let canvas = $("#resultadoPesquisa");
	let meuChart = new Chart(canvas, config);

	/*$("#pesquisar").click(function () {
		//Recupera dados dos campos
		let idMunicipio = $("#campoMunicipiosHistorico option:selected").val();
		let idBalneario = $("#campoLocaisHistorico option:selected").val();
		let ano = $("#campoAnoHistorico option:selected").val();
		let resultadoPesquisa = $("#resultadoPesquisa");

		//Esconde o campo da pesquisa
		resultadoPesquisa.hide("slow");
		$.ajax(
			{
				url: "requisicoes/request.php",
				type: "POST",
				//Adiciona os dados ao POST
				data: { idMunicipio : idMunicipio, idBalneario : idBalneario, ano : ano},
				//Define o tipo de dados como HTML
				dataType: 'json',
				async: true,
				success: function(dados) {
					//Esvazia o html, adiciona os dados e mostra o conteúdo
					resultadoPesquisa.empty();

					let tabelaDados = geraTabela(dados); //chamar a funcao que gera a tabela

					resultadoPesquisa.append(tabelaDados);
					resultadoPesquisa.toggle("slow");
				},
				error: function (erro) {
					alert("Oops deu algum erro na requisição <br>"+erro);
				}
			}
		);
	});*/

	$("#pesquisar-graph").click(function () {
		//Recupera dados dos campos
		let idMunicipio = $("#campoMunicipiosHistorico option:selected").val();
		let idBalneario = $("#campoLocaisHistorico option:selected").val();
		let ano = $("#campoAnoHistorico option:selected").val();

		// Partes da página onde serão adicionados conteúdos
		let resultadoPesquisa = $("#resultadoPesquisa");
		let opcoesGrafico = $("#opcoesGrafico");
		let listaPontosDeColeta = $("#listaPontosDeColeta");
		let legenda = $('#legenda');

		//Esconde o campo da pesquisa
		resultadoPesquisa.hide("slow");
		opcoesGrafico.hide("slow");
		listaPontosDeColeta.hide("slow");
		legenda.hide("slow");
		$.ajax(
			{
				// url: "requisicoes/reqTeste.php",
				url: "requisicoes/request.php",
				type: "POST",

				//Adiciona os dados ao POST
				data: { idMunicipio : idMunicipio, idBalneario : idBalneario, ano : ano},

				//Define o tipo de dados como JSON
				dataType: 'json',
				async: true,
				success: function(dados) {
					// console.log(dados);
					//Para cada busca, zera os pontos de coleta
					//Adiciona os pontos coletados em um array local para trabalhar nele
					pontosDeColeta = [];
					$(dados).each(function (index, coleta) {
						$(coleta.coletas).each(function (indexColeta) {
							coleta.coletas[indexColeta].agua = parseInt(coleta.coletas[indexColeta].agua.replace(' Cº',''));
							coleta.coletas[indexColeta].ar = parseInt(coleta.coletas[indexColeta].ar.replace(' Cº',''))
						});

						// Os dados vêm em ordem reversa. Reorganiza o array de coletas
						coleta.coletas.reverse();
						pontosDeColeta.push(coleta);
					});
					// console.log(pontosDeColeta);
					//Adiciona Checkboxes com os pontos de coleta
					adicionaPontos(pontosDeColeta);

					//Esvazia o html
					// resultadoPesquisa.empty();
					// let pontoSelecionado = $(".pontoDeColeta:checked").val();

					// console.log(pontoSelecionado);
					//Separa as coletas por data
					config.data.datasets = [];
					config.data.labels = [];
					$(pontosDeColeta[0].coletas).each(function (index, dados) {
						config.data.labels.push(dados.data);
					});

					meuChart.update();


					//Cria o chart
					// atualizaChart([pontosDeColeta[pontoSelecionado]], $("#opcoesDeDados option:selected").val());

					//Mostra o resultado
					listaPontosDeColeta.show(600);
					opcoesGrafico.show(900);
					legenda.show(900);
					resultadoPesquisa.toggle(1200);
				},
				error: function (erro) {
					alert("Oops deu algum erro na requisição. Veja o console de erros");
					console.log(erro);
				}
			}
		);
	});

	// Atualiza o chart de acordo com a opção selecionada
	$("#opcoesDeDados").change(function () {
		$("#legenda").empty();
		atualizaChart(pontosChecked, $("#opcoesDeDados option:selected").val())
	});

	//function geraTabela - recebe os dados json e cria uma tabela HTML
	/*function geraTabela(dados){
		let tabela = document.createElement('table');
		$(tabela).addClass('table');
		$.each(dados, function(key, pontoColeta){
			console.log(pontoColeta);
			let linha = document.createElement('tr');
			let celula = document.createElement('td');
			let texto = document.createTextNode(key);

			celula.appendChild(texto);
			linha.appendChild(celula);
			tabela.appendChild(linha);
		});

		return tabela;
	}*/

	// Cria dinamicamente os pontos de coleta
	function adicionaPontos(coletas) {
		// Esvazia os pontos
		$("#listaPontosDeColeta").empty();

		// Para cada ponto de coleta é criado um checkbox.
		$(coletas).each(function (index, coleta) {
			$("#listaPontosDeColeta").append(
				$("<div>").addClass("form-check form-check-inline mx-auto").append(
					$("<input>")
						.addClass("form-check-input pontoDeColeta")
						.attr({'type' : "checkbox", "id" : "Checkbox"+index, "value" : index})
						.on('click', this, verificaChecked)
				).append(
					$("<label>")
						.addClass("form-check-label")
						.attr("for", "Checkbox"+index)
						.text(coleta.titulos.Município
							+ ", "
							+ coleta.titulos.Balneário
							+ ", "
							+ coleta.titulos.Ponto_de_Coleta)
				));
		});
	}

	//Verifica quais pontos de coleta estão selecionados e os envia para o chart
	function verificaChecked (){
		pontosChecked = [];
		$(".pontoDeColeta:checked").each(function (index, pontoChecked) {
			pontosChecked.push(pontosDeColeta[$(pontoChecked).val()]);
		});
		atualizaChart(pontosChecked, $("#opcoesDeDados option:selected").val());
	}

	//Uma tabela para cada ponto
	// function criarChart(dados) {
	// 	$(dados).each(function (pontoDeColeta) {
	// 		console.log(dados[pontoDeColeta]);
	// 		let canvas = document.createElement('canvas');
	// 		// $("#resultadoPesquisa").text(dados) ;
	// 		let dataColetas = [];
	// 		let temperaturaAgua =[];
	// 		$(dados[pontoDeColeta].coletas).each(function (index, coleta) {
	// 			dataColetas.push(coleta.data + " - "+ coleta.hora);
	// 			temperaturaAgua.push(parseInt(coleta.agua.replace(' Cº','')));
	// 		});
	//
	// 		let config = {
	// 			// The type of chart we want to create
	// 			type: 'line',
	//
	// 			// The data for our dataset
	// 			data: {
	// 				// labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
	// 				labels: dataColetas,
	// 				datasets: [{
	// 					label: 'Temperatura da água (C°)',
	// 					backgroundColor: 'rgb(255, 99, 132)',
	// 					borderColor: 'rgb(99, 99, 99)',
	// 					// data: [0, 10, 5, 2, 20, 30, 45]
	// 					data: temperaturaAgua
	// 				}]
	// 			},
	//
	// 			// Configuration options go here
	// 			options: {
	// 				responsive: true,
	// 				title: {
	// 					display : true,
	// 					text : dados[pontoDeColeta].titulos.Balneário + " - "
	// 						+ dados[pontoDeColeta].titulos.Localização +" - "
	// 						+dados[pontoDeColeta].titulos.Município +" - "
	// 						+ dados[pontoDeColeta].titulos.Ponto_de_Coleta
	// 				}
	// 			}
	// 		};
	//
	// 		new Chart(canvas, config);
	// 		$("#resultadoPesquisa").append(canvas);
	// 	});
	// }

	// Adiciona legenda para as opções Maré, Chuva e Condição
	function adicionaLegenda(opcaoDeDado){
		// Seleciona a div com id legenda
		let legenda = $("#legenda");

		// Esvazia a div
		legenda.empty();

		// Mostra os dados utilizando os valores dos objetos auxiliares
		switch (opcaoDeDado) {
			case 'mare':
				$.each(Object.keys(niveisDeMare), function (index, valor) {
					legenda.append(
						$("<div>")
							.addClass("column")
							.text(valor + " = " + index)
					);
				});
				break;
			case 'chuva':
				$.each(Object.keys(niveisDeChuva), function (index, valor) {
					legenda.append(
						$("<div>")
							.addClass("column")
							.text(valor + " = " + index)
					);
				});
				break;
			case 'condicao':
				$.each(Object.keys(condicaoDaAgua), function (index, valor) {
					legenda.append(
						$("<div>")
							.addClass("column")
							.text(valor + " = " + index)
					);
				});
				break;
		}
	}

	// Retorna o maior valor (para limitar o gráfico)
	function max(val1, val2) {
		if (val1 > val2){
			return val1;
		} else {
			return val2;
		}
	}

	//Recria o chart com os pontos de coleta selecionados
	function atualizaChart(arrayPontosSelecionados, opcaoDeDado) {
		adicionaLegenda(opcaoDeDado);

		//Zera os datasets
		config.data.datasets = [];
		config.options.scales.yAxes[0].scaleLabel.labelString = "";

		//Se houver dados, adiciona ao dataset, Caso contrário, apenas dá o update com o dataset vazio
		if (arrayPontosSelecionados.length){
			//Percorre cada ponto para pegar os dados
			$(arrayPontosSelecionados).each(function (index, pontoSelecionado) {
				let nomeDaCor = nomesDasCores[config.data.datasets.length % nomesDasCores.length];
				let novaCor = coresDoGrafico[nomeDaCor];
				let dadosColeta = [];
				$(pontoSelecionado.coletas).each(function (index, dadoColeta) {
					// Adiciona os dados ao array temporario de dados de coleta
					switch (opcaoDeDado) {
						case 'mare':
							dadosColeta.push(niveisDeMare[dadoColeta[opcaoDeDado]]);
							break;
						case 'chuva':
							dadosColeta.push(niveisDeChuva[dadoColeta[opcaoDeDado]]);
							break;
						case 'condicao':
							dadosColeta.push(condicaoDaAgua[dadoColeta[opcaoDeDado]]);
							break;
						default:
							dadosColeta.push(dadoColeta[opcaoDeDado]);
							break;
					}
				});

				// Cria um dataset temporário com os dados que serão adicionados no gráfico
				let novoDataset = {
					label: pontoSelecionado.titulos.Balneário +" - "+ pontoSelecionado.titulos.Ponto_de_Coleta,
					backgroundColor: novaCor,
					borderColor: novaCor,
					data: dadosColeta,
					fill: false
				};

				config.data.datasets.push(novoDataset);
			});
		}
		// Atualiza a legenda do eixo y
		config.options.scales.yAxes[0].scaleLabel.labelString = $("#opcoesDeDados option:selected").text();
		meuChart.update();
	}
});
