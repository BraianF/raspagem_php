<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Requisição de dados</title>

	<script src="js/jquery-3.4.1.js"></script>

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="lib/chartjs/Chart.min.css">

	<script src="lib/chartjs/Chart.min.js"></script>
</head>
<body>
<div class="container-fluid">
	<!--	Lista de municípios e pontos. Copiado do site https://balneabilidade.ima.sc.gov.br/ -->
	<div class="row">
		<div class=" col-sm-12 col-md-12 col-lg-6 col-xl-6 rounded mx-auto my-5">
			<div class="border border-dark rounded">
				<div class="modal-header modal-info" id="modal-header">
					<h5 class="modal-title" id="modalHistoricoLabel">Histórico de Balneabilidade</h5>
				</div>
				<div class="modal-body">
					<label style="font-size: 12px">
						Selecione o município, o balneário, o ano e clique no botão "Verificar balenabilidade" para verificar a condição de balneabilidade do balneário.
					</label>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<label class="input-group-text" for="campoMunicipiosHistorico">Município</label>
						</div>
						<select class="custom-select" id="campoMunicipiosHistorico">
							<option value="0">Todos</option>
						</select>
					</div>

					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<label class="input-group-text" for="campoLocaisHistorico">Balneário</label>
						</div>
						<select class="custom-select" id="campoLocaisHistorico">
							<option value="0">Todos</option>
						</select>
					</div>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<label class="input-group-text" for="campoAnoHistorico">Ano</label>
						</div>
						<select class="custom-select" id="campoAnoHistorico">
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary active" id="pesquisar-graph">Verificar balenabilidade - Gráfico</button>
				</div>
			</div>
		</div>
	</div>
	
	<!--   	Pontos de coleta-->
	<div class="row my-3" id="listaPontosDeColeta" multiple="multiple" style="display: none">
	</div>
	
	<!--	Opções das variáveis-->
	<div class="row" id="opcoesGrafico" style="display: none">
		<div class="input-group mb-3 col-6 mx-auto" >
			<div class="input-group-prepend">
				<label class="input-group-text" for="opcoesDeDados">Opção</label>
			</div>
			<select class="custom-select" id="opcoesDeDados">
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
	<div class="row mx-auto text-center" id="legenda">
	</div>
	
	<!--	Lugar nde  gráfico é gerado-->
	<div class="row my-1">
		<canvas id="resultadoPesquisa" style="display: none">
		</canvas>
	</div>
	<footer class="my-5">
		Desenvolvido por <a href="https://github.com/BraianF" target="_blank">Braian Gabriel Antonilli</a>
		e <a href="https://github.com/quinnaty/" target="_blank">Nátaly Nazario Quina</a>.
		<br>
		Com grande auxílio de <a href="https://github.com/rafaelsperoni" target="_blank">Profº Rafael Speroni</a>,
		<a href="https://stackoverflow.com/" target="_blank">StackOverflow</a> e
		<a href="https://www.w3schools.com/" target="_blank">W3Schools</a>.
	</footer>
</div>
<script src="js/utils.js"></script>
<script src="js/recuperaDadosIMA.js"></script>
<script src="js/funcoes.js"></script>
</body>
</html>
