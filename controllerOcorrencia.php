<?php

	session_start();


	$mysqli = new mysqli('localhost', 'root', 'root', 'BD_FINAL') or die(mysqli_error($mysqli));

	$id = 0;
	$update = false;
	$tipo_atendimento = '';
	$grau_risco = '';
	$date = '';
	$status = '';
	$ocorrenc_end_id = '';



	
	if(isset($_POST['save'])){
		$tipo_atendimento = $_POST['tipo_atendimento'];
		$grau_risco = $_POST['grau_risco'];
		$status = $_POST['status'];
		$date = date("Y-m-d H:i:s");
		
		echo($status);

		
		

		$mysqli->query("INSERT INTO OCORRENCIA(ocorrencia_date, ocorrencia_status, ocorrencia_tipo_atendimento, ocorrencia_grau_risco) VALUES 
			('$date', '$status', '$tipo_atendimento', '$grau_risco')") or die($mysqli->error);
		


		header("location: ocorrencia.php");


	}


	if (isset($_GET['delete'])) {
		$id = $_GET['delete'];
		

		$mysqli->query("SET FOREIGN_KEY_CHECKS=0") or die($mysqli_error());

		$mysqli->query("DELETE FROM PACIENTE_OCORRENCIA WHERE paciente_ocorrencia_ocorrencia_id = $id");

		$mysqli->query("DELETE FROM ENDERECO WHERE endereco_id = (SELECT ocorrencia_endereco_id FROM OCORRENCIA WHERE ocorrencia_id = $id)") or die($mysqli->error());
		
		$mysqli->query("DELETE FROM OCORRENCIA WHERE ocorrencia_id = $id") or die($mysqli->error());

		$mysqli->query("SET FOREIGN_KEY_CHECKS=1") or die($mysqli_error());


		header("location: ocorrencia.php");

	}



	if(isset($_GET['edit'])) {
		$id = $_GET['edit'];
		$update = true;
		$result = $mysqli->query("SELECT * FROM OCORRENCIA WHERE ocorrencia_id = $id") or die($mysqli->error());
		if(count($result) ==1){
			$row = $result->fetch_array();
			$status = $row['ocorrencia_status'];
			$tipo_atendimento = $row['ocorrencia_tipo_atendimento'];
			$grau_risco = $row['ocorrencia_grau_risco'];
			$date = $row['ocorrencia_date'];
		}

	}



	if(isset($_POST['update'])) {
		$id = $_POST['id'];
		$status = $_POST['status'];
		$tipo_atendimento = $_POST['tipo_atendimento'];
		$grau_risco = $_POST['grau_risco'];
		$date = date("Y-m-d H:i:s");

		$mysqli->query("UPDATE OCORRENCIA SET
			ocorrencia_date = '$date',
			ocorrencia_status = '$status',
			ocorrencia_tipo_atendimento='$tipo_atendimento',
			ocorrencia_grau_risco='$grau_risco' WHERE ocorrencia_id = $id") or die($mysqli->error());



		header("location: ocorrencia.php");
	}


?>
