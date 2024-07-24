<!DOCTYPE html>
<html lang="en">
<head>
	<title>PHP-CRUD</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	
</head>
<body>
	<?php require_once __DIR__ .'/controllerSintomas.php'; ?>

	<div class="container">

		<?php
			$mysqli = new mysqli('localhost', 'root', 'root', 'BD_FINAL') or die(mysqli_error($mysqli));

			$idp = $_GET['id'];
			if(isset($idp)){
				$result = $mysqli->query("SELECT * FROM SINTOMAS WHERE sintomas_paciente_id = $idp") or die($mysqli->error);
			}
			else{
				$result = $mysqli->query("SELECT * FROM SINTOMAS") or die($mysqli->error);
			}
		?>

		<h1 style="text-align: center">Sintomas</h1>
		<br>
		<?php if(isset($idp)): ?>
		<h2 style="text-align: center">Paciente ID: <?php echo $idp;?></h2>
		<br>
		<?php endif; ?>	


	<div class="row justify-content-center">
		<table class="table">
			<thead>
				<tr>
					<th>Fratura</th>
					<th>Infarto</th>
					<th>Falta de Ar</th>
					<th>Tontura</th>
					<th>Queda</th>
					<th>Convulsão</th>
					<th>Acidente Carro</th>
					<th>AVC</th>
					<th colspan="2">Action</th>
				</tr>
			</thead>


		<?php while ($row = $result->fetch_assoc()): ?>

				<tr>
					<td><?php echo $row['sintomas_fratura']; ?></td>
					<td><?php echo $row['sintomas_infarto']; ?></td>
					<td><?php echo $row['sintomas_falta_ar']; ?></td>
					<td><?php echo $row['sintomas_tontura']; ?></td>
					<td><?php echo $row['sintomas_queda']; ?></td>
					<td><?php echo $row['sintomas_convulsao']; ?></td>
					<td><?php echo $row['sintomas_acidente_carro']; ?></td>
					<td><?php echo $row['sintomas_avc']; ?></td>
					<td>
						<a href="sintomas.php?edit=<?php echo $row['sintomas_id']?>&id=<?php echo $row['sintomas_paciente_id'] ?>" class = "btn btn-info">Edit</a>
						<a href="controllerSintomas.php?id=<?php echo $row['paciente_id']?>&delete=<?php echo $row['sintomas_id'] ?>" class="btn btn-danger">Delete</a>
					</td>
				</tr>
		<?php endwhile; ?>
		</table>
	</div>



	<div class="row justify-content-center">
		<form action="controllerSintomas.php" method="POST">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="hidden" name="idp" value="<?php echo $idp?>">
			<div class="form-group">
				<label><strong>Fratura</strong></label>
				<input type="text" name="fratura" class="form-control" value="<?php echo $fratura ?>">
			</div class="form-group">

			<div class="form-group">
				<label><strong>Infarto</strong></label>
				<input type="text" name="infarto" class="form-control" value="<?php echo $infarto ?>">
			</div>

			<div class="form-group">
				<label><strong>Falta de Ar</strong></label>
				<input type="text" name="falta_ar" class="form-control" value="<?php echo $falta_ar ?>">
			</div>

			<div class="form-group">
				<label><strong>Tontura</strong></label>
				<input type="text" name="tontura" class="form-control" value="<?php echo $tontura ?>">
			</div>

			<div class="form-group">
				<label><strong>Queda</strong></label>
				<input type="text" name="queda" class="form-control" value="<?php echo $queda ?>">
			</div>

			<div class="form-group">
				<label><strong>Convulsão</strong></label>
				<input type="text" name="convulsao" class="form-control" value="<?php echo $convulsao ?>">
			</div>

			<div class="form-group">
				<label><strong>Acidente de Carro</strong></label>
				<input type="text" name="acidente_carro" class="form-control" value="<?php echo $acidente_carro ?>">
			</div>

			<div class="form-group">
				<label><strong>AVC</strong></label>
				<input type="text" name="avc" class="form-control" value="<?php echo $avc ?>">
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