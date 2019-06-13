
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Requisição de dados</title>

	<script src="js/jquery-3.4.1.js"></script>

  <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js">
    </script>
	

   	<link rel="stylesheet" href="lib/chartjs/Chart.min.css">


	<script src="lib/chartjs/Chart.min.js"></script>
</head>
<body>
<div class="container">
	<!--	Lista de municípios e pontos. Copiado do site https://balneabilidade.ima.sc.gov.br/ -->
	<div class="row">
		<div class=class="center-align">
			<div class="col s10">
				<div class="modal-header modal-info" id="modal-header">
					<h5 class="modal-title" id="modalHistoricoLabel">Histórico de Balneabilidade</h5>
				</div>
				<div class="modal-body">
					<label style="font-size: 12px">
						Selecione o município, o balneário, o ano e clique no botão "Verificar balenabilidade" para verificar a condição de balneabilidade do balneário.
					</label>
					<div  class="input-field" value="" disabled selected> Município				
						<select class="browser-default" id="campoMunicipiosHistorico">
							<option value="0">Todos</option>
						</select>
					</div>

					<div  class="input-field" value="" disabled selected> Balneário
						
						<select class="browser-default" id="campoLocaisHistorico">
							<option value="0">Todos</option>
						</select>
					</div>
					<div class="input-field" value="" disabled selected> Ano
						<select class="browser-default" id="campoAnoHistorico">				
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="waves-effect waves-light btn-small" id="pesquisar-graph">Verificar balenabilidade - Gráfico</button>
				</div>
			</div>
		</div>
	</div>
	
	<!--   	Pontos de coleta-->
	<div class="row my-3" id="listaPontosDeColeta" multiple="multiple" >
	</div>
	
	<!--	Opções das variáveis-->
	<div class="row" id="opcoesGrafico">
		<div class="input-group mb-3 col-6 mx-auto" >
			<div class="input-group-prepend">
				<label class="input-group-text" for="opcoesDeDados">Opção</label>
			</div>
			<select class="browzer-default" id="opcoesDeDados">
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


        <footer class="page-footer">
        <div class="card-panel teal lighten-6">
          <div class="container">
            <div class="row">
              <div class="col l6 s12">
                <h5 class="white-text">Desenvolvido por</h5>
                <a class="grey-text text-lighten-3" href="https://github.com/BraianF">Braian Gabriel Antonilli</a>
              </div>
              <div class="col l4 offset-l2 s12">
                <h6 class="white-text">Com grande auxílio de</h6>
                <ul> 
                 <li><a class="grey-text text-lighten-3" href="https://github.com/rafaelsperoni">Profº Rafael Speroni</a></li>
                  <li><a class="grey-text text-lighten-3" href="https://stackoverflow.com/">StackOverflow</a></li>
                  <li><a class="grey-text text-lighten-3" href="https://www.w3schools.com/">W3Schools</a></li>
                </ul>
              </div>
            </div>
          </div>
          </div>     
          <div class="footer-copyright">
            <div class="container">
            Junho 2019
            </div>
          </div>
		</footer>
            
	<!-- <footer  class="page-footer">
		<div class="col l4 offset-l2 s12">
		Desenvolvido por <a href="https://github.com/BraianF" target="_blank">Braian Gabriel Antonilli</a>.
		<br>
		Com grande auxílio de <a href="https://github.com/rafaelsperoni" target="_blank">Profº Rafael Speroni</a>,
		<a href="https://stackoverflow.com/" target="_blank">StackOverflow</a> e
		<a href="https://www.w3schools.com/" target="_blank">W3Schools</a>.
		</div>
	</footer> -->
</div>
<script src="js/utils.js"></script>
<script src="js/recuperaDadosIMA.js"></script>
<script src="js/funcoes.js"></script>
<script>
		$(document).ready(function(){
    	$('select').formSelect();
    	});
</script>

</body>
</html>