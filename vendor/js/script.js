$(document).ready(function(){

	// Adicionar linhas para paradas
	$('#adicionar-parada').click(function(){
		var table 	= $(this).parents('table');
		var row 	= table.find('tbody tr:first-child');
		table.find('tbody').append("<tr>"+row.html()+"</tr>");

		// Adiciona a chave da parada
		table.find('tbody tr td:first-child').each(function(index){
			$(this).html(index+1);
		});
	});

	// Remover linha selecionada da parada
	$(document).on('click','#remover-parada',function(){
		var table 	= $(this).parents('table');

		if(table.find('tbody tr:last-child td:first-child').html() > 1){
			$(this).parents('tr').remove();
		}

		// Adiciona a chave da parada
		table.find('tbody tr td:first-child').each(function(index){
			$(this).html(index+1);
		});
	});

	// Adicionar linha para grupo
	$('#adicionar-grupo').click(function(){
		var table 	= $(this).parents('table');
		var row 	= table.find('tbody tr:first-child');
		table.find('tbody').append("<tr>"+row.html()+"</tr>");

		// Adiciona a chave do grupo
		table.find('tbody tr td:first-child').each(function(index){
			$(this).html(index+1);
		});
	});

	// Remover linha selecionada do grupo
	$(document).on('click','#remover-grupo',function(){
		var table 	= $(this).parents('table');

		if(table.find('tbody tr:last-child td:first-child').html() > 1){
			$(this).parents('tr').remove();
		}

		// Adiciona a chave do grupo
		table.find('tbody tr td:first-child').each(function(index){
			$(this).html(index+1);
		});
	});

	/*
	 * Validações
	 */
	$('#form').submit(function(){

		var retorno = true;

		var inicioCpu = parseInt($('#inicioCpu').val());

		$('#paradas tbody tr').each(function(index){
			index = index+1;

			var inicio 	= parseInt($(this).find('#inicioParada').val());
			var termino = parseInt($(this).find('#terminoParada').val());

			if(inicioCpu > inicio){
				$('#alertas').append('<div class="alert alert-danger"> <strong>Erro parada '+index+':</strong> O tempo da parada não pode ser menor que o inicio da CPU!');
				retorno = false;
			}

			if(inicio >= termino){
				$('#alertas').append('<div class="alert alert-danger"> <strong>Erro parada '+index+':</strong> O término da parada não pode ser menor ou igual que o inicio!');
				retorno = false;
			}

			$('#paradas tbody tr').each(function(index2){
				index2 = index2+1;

				if(index != index2){
					var inicioParada 	= parseInt($(this).find('#inicioParada').val());
					var terminoParada 	= parseInt($(this).find('#terminoParada').val());

					if((inicioParada <= inicio && inicio <= terminoParada) || (inicioParada <= termino && terminoParada > termino)){
						$('#alertas').append('<div class="alert alert-danger"> <strong>Erro parada '+index2+':</strong> Não são permitidos os valores da parada '+index2+'!');
						retorno = false;
					}
				}
			});

		});

		$('#processos tbody tr').each(function(index){
			index = index + 1;
			var tempoServico 	= parseInt($(this).find('#tempoServico').val());
			var qtdProcessos 	= parseInt($(this).find('#qtdProcessos').val());

			if(tempoServico <= 0){
				$('#alertas').append('<div class="alert alert-danger"> <strong>Erro processo '+index+':</strong> Não são permitidos tempo de serviço menor ou igual a 0!');
				retorno = false;
			}

			if(qtdProcessos <= 0){
				$('#alertas').append('<div class="alert alert-danger"> <strong>Erro processo '+index+':</strong> Não são permitidos quantidade de processos menor ou igual a 0!');
				retorno = false;
			}
		});

		return retorno;
	});


	/*
	 * Permitir que apenas numeros sejam digitados
	 */
	$(document).on('keyup', 'input', function(e){
 
		var thisVal = $(this).val();
		var tempVal = "";

		for(var i = 0; i<thisVal.length; i++){
			if(RegExp(/^[0-9]$/).test(thisVal.charAt(i))){
				tempVal += thisVal.charAt(i);

				if(e.keyCode == 8){
					tempVal = thisVal.substr(0,i);
				}						
			}
		}			
		$(this).val(tempVal);
	});

});