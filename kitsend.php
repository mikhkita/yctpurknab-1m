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

		if(sendMail($deafult, $arFields, "Поступила заявка на обратный звонок", true)){	
			echo "1";
		}else{
			echo "0";
		}
	}
?>