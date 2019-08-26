<?php
	require_once("mail.php");

	if( count($_POST) ){

		$deafult = array(
			"name" => "Контактное лицо",
			"phone" => "Телефон",
			"email" => "E-mail"
		);

		$arFields = array();
		foreach ($_POST as $key => $value){
			$arFields[$key] = htmlspecialchars($value);
		}

		$email_to = "rom4es.test@gmail.com";

		if(sendMail($email_to, $deafult, $arFields)){	
			echo "1";
		}else{
			echo "0";
		}
	}
?>