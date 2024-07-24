<?php


	session_start();


	$mysqli = new mysqli('localhost', 'root', 'root', 'BD_FINAL') or die(mysqli_error($mysqli));

	$id = 0;
	$update = false;
	$latitude = '';
	$longitude = '';
	$logradouro = '';
	$bairro = '';
	$municipio = '';

	$end_cod = '';

	
	if(isset($_POST['save'])){
		$latitude = $_POST['latitude'];
		$longitude = $_POST['longitude'];
		$logradouro = $_POST['logradouro'];
		$bairro = $_POST['bairro'];
		$municipio = $_POST['municipio'];
		$cod = $_POST['codp'];
		//print_r($cod);

		$result = $mysqli->query("SELECT municipio_cod FROM MUNICIPIO WHERE municipio_nome = '$municipio'") or die(mysqli_error($mysqli));
		//print_r($cod);
		$row = $result->fetch_assoc();
		//print_r($cod);
		$municipio_cod = $row['municipio_cod'];

		$mysqli->query("CALL end_ocorrencia('$latitude', '$longitude', '$logradouro', '$bairro', $municipio_cod, '$cod')") or die(mysqli_error($mysqli));



		header("location: enderecoOcorrencia.php");


	}


	if (isset($_GET['delete'])) {
		$id = $_GET['delete'];		


		$mysqli->query("UPDATE OCORRENCIA SET ocorrencia_endereco_id = null WHERE ocorrencia_endereco_id = $id") or die(mysqli_error($mysqli));

		$mysqli->query("DELETE FROM ENDERECO WHERE endereco_id = $id") or die($mysqli->error());

		header("location: enderecoOcorrencia.php");

	}



	if(isset($_GET['edit'])) {
		$id = $_GET['edit'];
		$result = $mysqli->query("SELECT * FROM ENDERECO WHERE endereco_id = $id") or die($mysqli->error());
		if(count($result) ==1){
			$update = true;
			$row = $result->fetch_array();
			$latitude = $row['endereco_latitude'];
			$longitude = $row['endereco_longitude'];
			$logradouro = $row['endereco_logradouro'];
			$bairro = $row['endereco_bairro'];
			$municipio = $row['endereco_municipio_cod'];
			//var_dump($municipio);

			$end_result = $mysqli->query("SELECT municipio_nome FROM MUNICIPIO WHERE municipio_cod = $municipio") or die(mysqli_error($mysqli));
			
			$end_row = $end_result->fetch_array();

			$municipio = $end_row['municipio_nome'];
			//var_dump($municipio);
			
		}

	}



	if(isset($_POST['update'])) {
		//var_dump($_POST);
		$id = $_POST['id'];
		$latitude = $_POST['latitude'];
		$longitude = $_POST['longitude'];
		$logradouro = $_POST['logradouro'];
		$bairro = $_POST['bairro'];
		$municipio = $_POST['municipio'];

		$end_result = $mysqli->query("SELECT municipio_cod FROM MUNICIPIO WHERE municipio_nome = '$municipio'") or die($mysqli->error());
		$end_row = $end_result->fetch_array();
		$end_cod = $end_row['municipio_cod'];

		$mysqli->query("UPDATE ENDERECO SET
			endereco_latitude='$latitude',
			endereco_longitude='$longitude',
			endereco_logradouro='$logradouro',
			endereco_bairro='$bairro',
			endereco_municipio_cod='$end_cod' WHERE endereco_id = $id") or die(mysqli_error($mysqli));



		header("location: enderecoOcorrencia.php");
	}


?>
