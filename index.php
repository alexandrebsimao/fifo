<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	
	<title>FIFO</title>
	<link rel="stylesheet" type="text/css" href="vendor/css/bootstrap.min.css">

</head>
<body>

	<div class="container">
		<form method="post" action="resultado.php" id="form">

			<h1>FIFO</h1>

			<div id="alertas"></div>

			<div class="row">
				<h4>CPU</h4>

				<div class="col-md-6">
					<label>Inicio da CPU</label>
					<input type="text" name="inicioCpu" id="inicioCpu" class="form-control" required="required" maxlength="10" />
				</div>
				<div class="col-md-6">
					<label>Inicio do Primeiro Processo</label>
					<input type="text" name="primeiroProcesso" id="primeiroProcesso" class="form-control" required="required" maxlength="10" />
				</div>

			</div>

			<br>

			<h4>Paradas</h4>

			<table class="table table-bordered" id="paradas">
				<thead>
					<tr>
						<th>Parada</th>
						<th>Inicio da Parada</th>
						<th>Término da Parada</th>
						<th>Remover</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td>
							<input type="text" name="inicioParada[]" id="inicioParada" class="form-control" maxlength="10" />
						</td>
						<td>
							<input type="text" name="terminoParada[]" id="terminoParada" class="form-control" maxlength="10" />
						</td>
						<td width="150">
							<button type="button" id="remover-parada" class="btn btn-danger">Remover Parada</button>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4">
							<button type="button" id="adicionar-parada" class="btn btn-primary">Adicionar Parada</button>
						</td>
					</tr>
				</tfoot>
			</table>

			<br>

			<h4>Processos</h4>

			<table class="table table-bordered" id="processos">
				<thead>
					<tr>
						<th>Grupo</th>
						<th>Qtd. Processos</th>
						<th>Intervalo de Chegada</th>
						<th>Tempo de Serviço</th>
						<th>Remover</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>1</td>
						<td>
							<input type="text" name="qtdProcessos[]" id="qtdProcessos" class="form-control" required="required" maxlength="10" />
						</td>
						<td>
							<input type="text" name="intChegada[]" id="intChegada" class="form-control" required="required" maxlength="10" />
						</td>
						<td>
							<input type="text" name="tempoServico[]" id="tempoServico" class="form-control" required="required" maxlength="10" />
						</td>
						<td width="150">
							<button type="button" id="remover-grupo" class="btn btn-danger">Remover Grupo</button>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5">
							<button type="button" id="adicionar-grupo" class="btn btn-primary">Adicionar Grupo</button>
						</td>
					</tr>
				</tfoot>
			</table>

			<button type="submit" class="btn btn-success">Enviar</button>

		</form>

	</div>

	<script type="text/javascript" src="vendor/js/jquery.min.js"></script>
	<script type="text/javascript" src="vendor/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="vendor/js/script.js"></script>
</body>
</html>