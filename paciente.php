<!DOCTYPE html>
<html lang="en">
<head>
	<title>PHP-CRUD</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	
</head>
<body>

	<?php  __DIR__."/controllerPaciente2.php";?>

	<div class="container">
		

		<?php
			$id_ocorrencia = $_GET['id'];
			
			$mysqli = new mysqli('localhost', 'root', 'root', 'BD_FINAL') or die(mysqli_error($mysqli));


			if(isset($id_ocorrencia)){
				$result1 = $mysqli->query("SELECT paciente_ocorrencia_paciente_id FROM PACIENTE_OCORRENCIA WHERE paciente_ocorrencia_ocorrencia_id = '$id_ocorrencia'");
			}
			else{
				$result = $mysqli->query("SELECT * FROM PACIENTE") or die($mysqli->error);
			}
			

			
			
		?>

	<h1 style="text-align: center">Formulário Paciente</h1>
	<br>
	<?php if(isset($id_ocorrencia)): ?>
		<h2 style="text-align: center">Paciente ID: <?php echo $id_ocorrencia;?></h2>
		<br>
		<?php endif; ?>	


	<div class="row justify-content-center">
		<table class="table">
			<thead>
				<tr>
					<th>Nome</th>
					<th>Sobrenome</th>
					<th>Sexo</th>
					<th>Idade</th>
					<th>CPF</th>
					<th>RG</th>
					<th>Telefone</th>
					<th colspan="4">Action</th>
				</tr>
			</thead>


		<?php while ($row1 = $result1->fetch_assoc()): ?>

		<?php $pac_id = $row1['paciente_ocorrencia_paciente_id'];
			$result = $mysqli->query("SELECT * FROM PACIENTE WHERE paciente_id = $pac_id") or die(mysqli_error($mysqli));
		?>

			<?php while($row = $result->fetch_assoc()): ?>

				<tr>
					<td><?php echo $row['paciente_nome']; ?></td>
					<td><?php echo $row['paciente_sobrenome']; ?></td>
					<td><?php echo $row['paciente_sexo']; ?></td>
					<td><?php echo $row['paciente_idade']; ?></td>
					<td><?php echo $row['paciente_cpf']; ?></td>
					<td><?php echo $row['paciente_rg']; ?></td>
					<td><?php echo $row['paciente_telefone']; ?></td>
					<td>
						<a href="enderecoPaciente.php?cod=<?php echo $row['paciente_id']?>" class = "btn btn-dark">Endereço</a>
						<a href="sintomas.php?id=<?php echo $row['paciente_id']?>" class = "btn btn-secondary">Sintomas</a>
						<a href="paciente.php?edit=<?php echo $row['paciente_id'] ?>" class = "btn btn-info">Edit</a>
						<a href="controllerPaciente2.php?delete=<?php echo $row['paciente_id']; ?>&delete_end=<?php echo $row['paciente_endereco_id']; ?>" class="btn btn-danger">Delete</a>
					</td>
				</tr>
			<?php endwhile; ?>
		<?php endwhile; ?>
		</table>
	</div>



	<div class="row justify-content-center">
		<form action="controllerPaciente2.php" method="POST">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="hidden" name="id_ocorrencia" value="<?php echo $id_ocorrencia; ?>">

			<div class="form-group">
				<label><strong>Nome</strong></label>
				<input type="text" name="nome" class="form-control" value="<?php echo $nome ?>">
			</div class="form-group">

			<div class="form-group">
				<label><strong>Sobrenome</strong></label>
				<input type="text" name="sobrenome" class="form-control" value="<?php echo $sobrenome ?>">
			</div>

			<div class="form-group">
				<label><strong>Sexo</strong></label>
				<input type="text" name="sexo" class="form-control" value="<?php echo $sexo ?>">
			</div>

			<div class="form-group">
				<label><strong>Idade</strong></label>
				<input type="text" name="idade" class="form-control" value="<?php echo $idade ?>">
			</div>

			<div class="form-group">
				<label><strong>CPF</strong></label>
				<input type="text" name="cpf" class="form-control" value="<?php echo $cpf ?>">
			</div>

			<div class="form-group">
				<label><strong>RG</strong></label>
				<input type="text" name="rg" class="form-control" value="<?php echo $rg ?>">
			</div>

			<div class="form-group">
				<label><strong>Telefone</strong></label>
				<input type="text" name="telefone" class="form-control" value="<?php echo $telefone ?>">
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