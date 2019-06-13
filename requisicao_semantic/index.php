<!doctype html>
<html lang="pt">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/semantic.min.css">
	<link rel="stylesheet" href="css/dropdown.min.css">
	<link rel="stylesheet" href="css/grid.min.css">
	
	<script src="js/jquery-3.4.1.js"></script>
	<script src="js/semantic.min.js"></script>
	<script src="js/dropdown.min.js"></script>
	
	<link rel="stylesheet" href="lib/chartjs/Chart.min.css">
	<script src="lib/chartjs/Chart.min.js"></script>

	<style type="text/css">
		body {
			background-color: #DADADA;
		}
		body > .grid {
			/*height: 75%;*/
		}
		.column {
			max-width: 450px;
		}
		.ui.grid{
			margin: 3em 1em;
		}
	</style>
</head>
<body>


	<div class="ui middle aligned center aligned grid">
		<div class="column">
			<h2 class="ui teal image header">
				<div class="content">
					<i class="life ring icon"></i>
					Histórico de Balneabilidade
				</div>
			</h2>
			<form class="ui large form">
				<div class="ui stacked segment">
					<div class="field">
						<select class="ui search dropdown" id="campoMunicipiosHistorico">
								<option value="0">Todos</option>
							</select>
					</div>
					<div class="field">
						<select class="ui search dropdown" id="campoLocaisHistorico">
							<option value="0">Todos</option>
						</select>
					</div>
					<div class="field">
						<select class="ui search dropdown" id="campoAnoHistorico">
						</select>
					</div>
					<div class="ui fluid large teal submit button" id="pesquisar-graph">
						<i class="icon search"></i>
						Pesquisar
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="ui middle aligned center aligned grid">
		<!--   	Pontos de coleta-->
		<div class="row">

			<div class="" id="listaPontosDeColeta" multiple="multiple" style="display: none">
			</div>
		</div>
<!--		<select class="ui fluid search dropdown" id="listaPontosDeColeta" multiple="" style="display: none">-->
<!--		</select>-->

		<!--	Opções das variáveis-->
		<div class="row" id="opcoesGrafico" style="display: none">
			<div class="ui labeled search icon" >
<!--				<div class="input-group-prepend">-->
<!--					<label class="input-group-text" for="opcoesDeDados">Opção</label>-->
<!--				</div>-->
				<i class="filter icon"></i>
				<span class="text">Opção: </span>
				<select class="ui search dropdown" id="opcoesDeDados">
					<option value="mare">Maré</option>
					<option value="chuva">Chuva</option>
					<option value="agua">Água (Cº)</option>
					<option value="ar">Ar (Cº)</option>
					<option value="ecoli">E.Coli NMP*/100ml</option>
					<option value="condicao">Condição</option>
				</select>
			</div>
		</div>
		<!--	Legenda de dados que não podem ser expressados puramente por números-->
		<div class="equal width row" id="legenda">
			
		</div>

		<!--	Lugar nde  gráfico é gerado-->
		<div class="row">
			<canvas id="resultadoPesquisa" style="display: none">
			</canvas>
		</div>
	</div>

	
	<footer>
		<div class="ui container">
			Desenvolvido por <a href="https://github.com/BraianF" target="_blank">Braian Gabriel Antonilli</a>.
			<br>
			Com grande auxílio de <a href="https://github.com/rafaelsperoni" target="_blank">Profº Rafael Speroni</a>,
			<a href="https://stackoverflow.com/" target="_blank">StackOverflow</a> e
			<a href="https://www.w3schools.com/" target="_blank">W3Schools</a>.
		</div>
		
	</footer>
	<script src="js/utils.js"></script>
	<script src="js/recuperaDadosIMA.js"></script>
	<script src="js/funcoes.js"></script>
	<script>
		$('.ui.dropdown')
			.dropdown()
		;
	</script>
</body>
</html>