<?php


	session_start();


	$mysqli = new mysqli('localhost', 'root', 'root', 'BD_FINAL') or die(mysqli_error($mysqli));

	$id = 0;
	$update = false;
	$fratura = '';
	$infarto = '';
	$falta_ar = '';
	$tontura = '';
	$queda = '';
	$convulsao = '';
	$paciente_id = '';
	$acidente_carro = '';
	$avc = '';


	
	if(isset($_POST['save'])){
		$fratura = $_POST['fratura'];
		$infarto = $_POST['infarto'];
		$falta_ar = $_POST['falta_ar'];
		$tontura = $_POST['tontura'];
		$queda = $_POST['queda'];
		$convulsao = $_POST['convulsao'];
		$acidente_carro = $_POST['acidente_carro'];
		$avc = $_POST['avc'];
		$paciente_id = $_POST['idp'];

		

		$mysqli->query("INSERT INTO SINTOMAS(sintomas_fratura, sintomas_infarto, sintomas_falta_ar, sintomas_tontura, sintomas_queda, sintomas_convulsao, sintomas_acidente_carro, sintomas_avc, sintomas_paciente_id) VALUES 
			('$fratura', '$infarto', '$falta_ar', '$tontura', '$queda', '$convulsao', '$acidente_carro', '$avc', '$paciente_id')") or die($mysqli->error);
		


		header("location: sintomas.php?id=$paciente_id");


	}


	if (isset($_GET['delete'])) {
		$id = $_GET['delete'];

		$mysqli->query("SET FOREIGN_KEY_CHECKS=0") or die($mysqli_error());
		
		$mysqli->query("DELETE FROM SINTOMAS WHERE sintomas_id = $id") or die($mysqli->error());

		$mysqli->query("SET FOREIGN_KEY_CHECKS=1") or die($mysqli_error());

		header("location: sintomas.php");

	}



	if(isset($_GET['edit'])) {
		$id = $_GET['edit'];
		$update = true;
		$result = $mysqli->query("SELECT * FROM SINTOMAS WHERE sintomas_id = $id") or die($mysqli->error());
		if(count($result) ==1){
			$row = $result->fetch_array();
			$fratura = $row['sintomas_fratura'];
			$infarto = $row['sintomas_infarto'];
			$falta_ar = $row['sintomas_falta_ar'];
			$tontura = $row['sintomas_tontura'];
			$queda = $row['sintomas_queda'];
			$convulsao = $row['sintomas_convulsao'];
			$acidente_carro = $row['sintomas_acidente_carro'];
			$avc = $row['sintomas_avc'];
			$paciente_id = $row['sintomas_paciente_id'];
		}

	}



	if(isset($_POST['update'])) {
		$id = $_POST['id'];
		$fratura = $_POST['fratura'];
		$infarto = $_POST['infarto'];
		$falta_ar = $_POST['falta_ar'];
		$tontura = $_POST['tontura'];
		$queda = $_POST['queda'];
		$convulsao = $_POST['convulsao'];
		$acidente_carro = $_POST['acidente_carro'];
		$avc = $_POST['avc'];

		$mysqli->query("UPDATE SINTOMAS SET
			sintomas_fratura='$fratura',
			sintomas_infarto='$infarto',
			sintomas_falta_ar='$falta_ar',
			sintomas_tontura='$tontura',
			sintomas_queda='$queda',
			sintomas_convulsao='$convulsao',
			sintomas_acidente_carro = '$acidente_carro',
			sintomas_avc = '$avc' WHERE sintomas_id = $id") or die($mysqli->error());



		header("location: sintomas.php?id=$id");
	}

?>