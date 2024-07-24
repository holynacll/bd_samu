<!DOCTYPE html>
<html lang="en">
<head>
	<title>PHP-CRUD</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	
</head>
<body>
	<?php require_once __DIR__ .'/controllerOcorrencia.php'; ?>

	<div class="container">
		

		<?php
			$mysqli = new mysqli('localhost', 'root', 'root', 'BD_FINAL') or die(mysqli_error($mysqli));
			
			$result = $mysqli->query("SELECT * FROM OCORRENCIA") or die($mysqli->error);	
			
		?>
		
		<h1 style="text-align: center">Ocorrências</h1>
		<br>
	

	<div class="row justify-content-center">
		<table class="table">
			<thead>
				<tr>
					<th>Status</th>
					<th>Tipo do Atendimento</th>
					<th>Grau de Risco</th>
					<th>Data</th>
					<th colspan="4">Action</th>
				</tr>
			</thead>


		<?php while ($row = $result->fetch_assoc()): ?>

				<tr>
					<td><?php echo $row['ocorrencia_status']; ?></td>
					<td><?php echo $row['ocorrencia_tipo_atendimento']; ?></td>
					<td><?php echo $row['ocorrencia_grau_risco']; ?></td>
					<td><?php echo $row['ocorrencia_date']; ?></td>
					<td>
						<a href="paciente.php?id=<?php echo $row['ocorrencia_id']?>" class = "btn btn-dark">Paciente</a>
						<a href="enderecoOcorrencia.php?cod=<?php echo $row['ocorrencia_id']?>" class = "btn btn-secondary">Endereço</a>
						<a href="ocorrencia.php?edit=<?php echo $row['ocorrencia_id'] ?>" class = "btn btn-info">Edit</a>
						<a href="controllerOcorrencia.php?delete=<?php echo $row['ocorrencia_id']; ?>" class="btn btn-danger">Delete</a>
					</td>
				</tr>
		<?php endwhile; ?>
		</table>
	</div>



	<div class="row justify-content-center">
		<form action="controllerOcorrencia.php" method="POST">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="hidden" name="date" value="<?php echo $date ?>">

			
			<div class="form-group">
				<label><strong>Status</strong></label>
				<input type="text" name="status" class="form-control" value="<?php echo $status ?>">
			</div class="form-group">
			
			<div class="form-group">
				<label><strong>Tipo de Atendimento</strong></label>
				<input type="text" name="tipo_atendimento" class="form-control" value="<?php echo $tipo_atendimento ?>">
			</div class="form-group">

			<div class="form-group">
				<label><strong>Grau de Risco</strong></label>
				<input type="text" name="grau_risco" class="form-control" value="<?php echo $grau_risco ?>">
			</div>

			<div class="form-group">
				<?php if($update == true): ?>
					<button type="submit" class="btn btn-info" name="update">Update</button>
				<?php else: ?>
					<button type="submit" class="btn btn-primary" name="save">Save</button>
				<?php endif; ?>
			</div>
		</form>
	</div>




	
	</div>





	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
