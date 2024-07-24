<?php


	session_start();

	

	$mysqli = new mysqli('localhost', 'root', 'root', 'BD_FINAL') or die(mysqli_error($mysqli));

	$id = 0;
	$update = false;
	$nome_fantasia = '';
	$cnes = '';


	
	if(isset($_POST['save'])){
		$nome_fantasia = $_POST['nome_fantasia'];
		$cnes = $_POST['cnes'];
		
		$mysqli->query("INSERT INTO HOSPITAL(hospital_nome_fantasia, hospital_cnes) VALUES 
			('$nome_fantasia', '$cnes')") or die($mysqli->error);
		


		header("location: hospital.php");


	}


	if (isset($_GET['delete'])) {
		$id = $_GET['delete'];
		$end_id = $_GET['delete_end'];


		$mysqli->query("SET FOREIGN_KEY_CHECKS=0") or die($mysqli_error());

		$mysqli->query("DELETE FROM ENDERECO WHERE endereco_id = $end_id") or die($mysqli->error());
		
		$mysqli->query("DELETE FROM HOSPITAL WHERE hospital_id = $id") or die($mysqli->error());

		$mysqli->query("SET FOREIGN_KEY_CHECKS=1") or die($mysqli_error());


		header("location: hospital.php");

	}



	if(isset($_GET['edit'])) {
		$id = $_GET['edit'];
		$update = true;
		$result = $mysqli->query("SELECT * FROM HOSPITAL WHERE hospital_id = $id") or die($mysqli->error());
		if(count($result) ==1){
			$row = $result->fetch_array();
			$nome_fantasia = $row['hospital_nome_fantasia'];
			$cnes = $row['hospital_cnes'];
		}

	}



	if(isset($_POST['update'])) {
		$id = $_POST['id'];
		$nome_fantasia = $_POST['nome_fantasia'];
		$cnes = $_POST['cnes'];
		

		$mysqli->query("UPDATE HOSPITAL SET
			hospital_nome_fantasia = '$nome_fantasia',
			hospital_cnes='$cnes' WHERE hospital_id = $id") or die($mysqli->error());



		header("location: hospital.php");
	}


?>