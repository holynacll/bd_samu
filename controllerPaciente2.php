<?php


	session_start();

	

	$mysqli = new mysqli('localhost', 'root', 'root', 'BD_FINAL') or die(mysqli_error($mysqli));

	$id = 0;
	$update = false;
	$nome = '';
	$sobrenome = '';
	$sexo = '';
	$idade = '';
	$cpf = '';
	$rg = '';
	$telefone = '';



	
	if(isset($_POST['save'])){
		$nome = $_POST['nome'];
		$sobrenome = $_POST['sobrenome'];
		$sexo = $_POST['sexo'];
		$idade = $_POST['idade'];
		$cpf = $_POST['cpf'];
		$rg = $_POST['rg'];
		$telefone = $_POST['telefone'];
		$id_ocorrencia = $_POST['id_ocorrencia'];
		
		

		$mysqli->query("CALL pac_ocorrenc('$nome', '$sobrenome', '$sexo', '$idade', '$rg', '$cpf', '$telefone', '$id_ocorrencia')") or die(mysqli_error($mysqli));
		


		header("location: paciente.php?id=$id_ocorrencia");


	}


	if (isset($_GET['delete'])) {
		$id = $_GET['delete'];


		$mysqli->query("SET FOREIGN_KEY_CHECKS=0") or die($mysqli_error());

		$mysqli->query("UPDATE SINTOMAS SET sintomas_paciente_id = 0 WHERE sintomas_paciente_id = $id");
		
		$mysqli->query("DELETE FROM PACIENTE WHERE paciente_id = $id") or die($mysqli->error());

		$mysqli->query("SET FOREIGN_KEY_CHECKS=1") or die($mysqli_error());


		header("location: paciente.php");

	}



	if(isset($_GET['edit'])) {
		$id = $_GET['edit'];
		$update = true;
		$result = $mysqli->query("SELECT * FROM PACIENTE WHERE paciente_id = $id") or die($mysqli->error());
		if(count($result) ==1){
			$row = $result->fetch_array();
			$nome = $row['paciente_nome'];
			$sobrenome = $row['paciente_sobrenome'];
			$sexo = $row['paciente_sexo'];
			$idade = $row['paciente_idade'];
			$rg = $row['paciente_rg'];
			$cpf = $row['paciente_cpf'];
			$telefone = $row['paciente_telefone'];
		}

	}



	if(isset($_POST['update'])) {
		$id = $_POST['id'];
		$nome = $_POST['nome'];
		$sobrenome = $_POST['sobrenome'];
		$sexo = $_POST['sexo'];
		$idade = $_POST['idade'];
		$rg = $POST['rg'];
		$cpf = $_POST['cpf'];
		$telefone = $_POST['telefone'];

		$mysqli->query("UPDATE PACIENTE SET
			paciente_nome='$nome',
			paciente_sobrenome='$sobrenome',
			paciente_sexo ='$sexo',
			paciente_idade='$idade',
			paciente_rg = '$rg',
			paciente_cpf='$cpf',
			paciente_telefone='$telefone' WHERE paciente_id = $id") or die($mysqli->error());



		header("location: paciente.php");
	}


?>