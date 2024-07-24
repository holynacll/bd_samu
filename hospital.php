<!DOCTYPE html>
<html lang="en">
<head>
	<title>PHP-CRUD</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	
</head>
<body>
	<?php require_once __DIR__ .'/controllerHospital.php'; ?>

	<div class="container">
		<h1 style="text-align: center">Formulário Paciente</h1>
		<br>

		<?php
			$mysqli = new mysqli('localhost', 'root', 'root', 'BD_FINAL') or die(mysqli_error($mysqli));

			$result = $mysqli->query("SELECT * FROM HOSPITAL") or die($mysqli->error);
			
		?>

	


	<div class="row justify-content-center">
		<table class="table">
			<thead>
				<tr>
					<th>Nome Fantasia</th>
					<th>CNES</th>
					<th colspan="2">Action</th>
				</tr>
			</thead>


		<?php while ($row = $result->fetch_assoc()): ?>

				<tr>
					<td><?php echo $row['hospital_nome_fantasia']; ?></td>
					<td><?php echo $row['hospital_cnes']; ?></td>
	
					<td>
						<a href="enderecoHospital.php?cod=<?php echo $row['hospital_id']?>" class = "btn btn-dark">Endereço</a>
						<a href="hospital.php?edit=<?php echo $row['hospital_id'] ?>" class = "btn btn-info">Edit</a>
						<a href="controllerHospital.php?delete=<?php echo $row['hospital_id']; ?>&delete_end=<?php echo $row['hospital_endereco_id']; ?>" class="btn btn-danger">Delete</a>
					</td>
				</tr>
		<?php endwhile; ?>
		</table>
	</div>



	<div class="row justify-content-center">
		<form action="controllerHospital.php" method="POST">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<div class="form-group">
				<label><strong>Nome Fantasia</strong></label>
				<input type="text" name="nome_fantasia" class="form-control" value="<?php echo $nome_fantasia ?>">
			</div class="form-group">

			<div class="form-group">
				<label><strong>CNES</strong></label>
				<input type="text" name="cnes" class="form-control" value="<?php echo $cnes ?>">
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