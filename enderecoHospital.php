<!DOCTYPE html>
<html lang="en">
<head>
	<title>PHP-CRUD</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	
</head>
<body>
	<?php require_once __DIR__ .'/controllerEnderecoHospital.php'; ?>

	<div class="container">
		
		<?php
			$mysqli = new mysqli('localhost', 'root', 'root', 'BD_FINAL') or die(mysqli_error($mysqli));

			$hosp_cnes = '';
			$hosp_end_id = 0;

			$codp = $_GET['cod'];
			if(isset($codp)) {
				$result1 = $mysqli->query("SELECT hospital_cnes, hospital_endereco_id FROM HOSPITAL WHERE hospital_id = $codp") or die($mysqli->error);
				$row1 = $result1->fetch_assoc();
				$hosp_cnes = $row1['hospital_cnes'];
				if(isset($row1['hospital_endereco_id'])){
					$hosp_end_id = $row1['hospital_endereco_id'];
				}
					$result = $mysqli->query("SELECT * FROM ENDERECO WHERE endereco_id = $hosp_end_id") or die($mysqli->error);	
			}
			else {
				$result = $mysqli->query("SELECT * FROM ENDERECO") or die($mysqli->error);
			}
		?>

		
		<h1 style="text-align: center">Formulário Endereço</h1>
		<br>
		<?php if(isset($codp)): ?>
			<h2 style="text-align: center">Hospital CNES: <?php echo $hosp_cnes;?></h2>
			<br>
		<?php endif; ?>	



	<div class="row justify-content-center">
		<table class="table">
			<thead>
				<tr>
					<th>Latitude</th>
					<th>Longitude</th>
					<th>Logradouro</th>
					<th>Bairro</th>
					<th>Municipio</th>
					<th colspan="2">Action</th>
				</tr>
			</thead>


		<?php while ($row = $result->fetch_assoc()): 
			$mun_cod =  $row['endereco_municipio_cod'];
			$mun_result = $mysqli->query("SELECT municipio_nome FROM MUNICIPIO WHERE municipio_cod = $mun_cod") or die($mysqli->error);
			$mun_row = $mun_result->fetch_assoc();
			$mun_nome = $mun_row['municipio_nome'];
		?>

				<tr>
					<td><?php echo $row['endereco_latitude']; ?></td>
					<td><?php echo $row['endereco_longitude']; ?></td>
					<td><?php echo $row['endereco_logradouro']; ?></td>
					<td><?php echo $row['endereco_bairro']; ?></td>
					<td><?php echo $mun_nome; ?></td>
					<td>
						<a href="enderecoHospital.php?edit=<?php echo $row['endereco_id'] ?>" class = "btn btn-info">Edit</a>
						<a href="controllerEnderecoHospital.php?delete=<?php echo $row['endereco_id']; ?>" class="btn btn-danger">Delete</a>
					</td>
				</tr>
		<?php endwhile; ?>
		</table>
	</div>



	<div class="row justify-content-center">
		<form action="controllerEnderecoHospital.php" method="POST">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="hidden" name="codp" value="<?php echo $codp?>">
			<div class="form-group">
				<label><strong>Latitude</strong></label>
				<input type="text" name="latitude" class="form-control" value="<?php echo $latitude ?>">
			</div class="form-group">

			<div class="form-group">
				<label><strong>Longitude</strong></label>
				<input type="text" name="longitude" class="form-control" value="<?php echo $longitude ?>">
			</div>

			<div class="form-group">
				<label><strong>Logradouro</strong></label>
				<input type="text" name="logradouro" class="form-control" value="<?php echo $logradouro ?>">
			</div>

			<div class="form-group">
				<label><strong>Bairro</strong></label>
				<input type="text" name="bairro" class="form-control" value="<?php echo $bairro ?>">
			</div>

			<div class="form-group">
				<label><strong>Municipio</strong></label>
				<input type="text" name="municipio" class="form-control" value="<?php echo $municipio ?>">
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