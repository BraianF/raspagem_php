//Recupera a lista de municípios e adiciona ao campo de seleção
function getMunicipios(){
	$.ajax(
		{
			url: "requisicoes/getMunicipios.php",
			type: "POST",
			data: "",
			dataType: 'json',
			async: true,
			success: function(municipios) {
				//Adiciona cada município a uma <option>
				$.each(municipios, function(id, municipio){
					$("#campoMunicipiosHistorico").append(
						$("<option></option>").val(municipio.CODIGO).text(municipio.DESCRICAO)
					)
				});
			},
			error: function (erro) {
				alert("Oops deu algum erro na requisição <br>"+erro);
			}
		}
	);
}

//Recupera os balnearios do município selecionado
function getLocaisByMunicipio(idmunicipio) {
	$.ajax(
		{
			url: "requisicoes/getLocaisByMunicipio.php",
			type: "POST",
			data: {'idMunicipio' : idmunicipio},
			dataType: 'json',
			async: true,
			success: function(locais) {
				$("#campoLocaisHistorico").empty();
				$("#campoLocaisHistorico").append(
					$("<option></option>").val("0").text("Todos")
				);
				$.each(locais, function(id, local){
					$("#campoLocaisHistorico").append(
						$("<option></option>").val(local.CODIGO).text(local.BALNEARIO)
					)
				});
			},
			error: function (erro) {
				alert("Oops deu algum erro na requisição <br>"+erro);				}
		}
	);
}

function anosAnalisados() {
	$.ajax(
		{
			url: "requisicoes/anosAnalisados.php",
			type: "POST",
			data: "",
			dataType: 'json',
			async: true,
			success: function(anos) {
				$.each(anos, function(id, ano){
					$("#campoAnoHistorico").append(
						$("<option></option>").val(ano.ANO).text(ano.ANO)
					)
				});
			},
			error: function (erro) {
				alert("Oops deu algum erro na requisição <br>"+erro);				}
		}
	);
}

//Ao mudar, carrega a lista de balneários
$("#campoMunicipiosHistorico").change(function () {
	//Carrega a lista de balneários baseando-se na cidade escolhida
	getLocaisByMunicipio(
		$("#campoMunicipiosHistorico option:selected").val()
	)
});