<?php 
	include "processa.php"; 
	$processa = new Processa();
	$grupos = $processa->criarProcessosPorGrupo();

	if(!$grupos)
		header("Location:index.php");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">

	<title>FIFO</title>

	<link rel="stylesheet" type="text/css" href="vendor/css/bootstrap.min.css">
</head>
<body>

	<div class="container">


		<h1>FIFO</h1>

		<a href="arquivos/<?php echo $processa->_fileName; ?>.csv" class="btn btn-default">Download .csv</a>

		<br><br>

		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Grupo</th>
					<th>Processo</th>
					<th>Inst. Chegada</th>
					<th>Tempo de Serviço</th>
					<th>Inst. Atendimento</th>
					<th>Tempo Perm. Fila</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($grupos as $grupo => $processos): ?>
					<tr>
						<th colspan="6">Grupo <?php echo $grupo+1; ?></th>
					</tr>
					<?php foreach($processos as $processo): ?>
						<tr>
							<td><?php echo $processo['grupoProcesso']+1; ?></td>	
							<td><?php echo $processo['processo']+1; ?></td>	
							<td><?php echo $processo['instanciaChegada']; ?></td>	
							<td><?php echo $processo['tempoServico']; ?></td>	
							<td><?php echo $processo['instanciaAtendimento']; ?></td>	
							<td><?php echo $processo['tempoPermanenciaFila']; ?></td>	
						</tr>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</tbody>
		</table>

		<a href="index.php">Voltar</a>

	</div>
</body>
</html>