<?php
require_once("mail.php");

if( count($_POST) ){
	$arFields = array();
	$arFields['applicant'] = htmlspecialchars($_POST["applicant"]);
	$arFields['debtor'] = htmlspecialchars($_POST["debtor"]);
	if($arFields['debtor'] == "physical"){
		$arFields['name'] = htmlspecialchars($_POST["name"]);
	}else{
		$arFields['INN'] = htmlspecialchars($_POST["INN"]);
	}
	$arFields['phone'] = htmlspecialchars($_POST["phone"]);
	$arFields['email'] = htmlspecialchars($_POST["email"]);
	$arFields['subject'] = htmlspecialchars($_POST["subject"]);

	//Сохранить данные в сессии
	session_start();
	unset($_SESSION['id']);
	unset($_SESSION['error']);

	$_SESSION['applicant'] = $arFields['applicant'];
	$_SESSION['debtor'] = $arFields['debtor'];
	$_SESSION['name'] = isset($arFields['name']) ? $arFields['name'] : "";
	$_SESSION['INN'] = isset($arFields['INN']) ? $arFields['INN'] : "";
	$_SESSION['phone'] = $arFields['phone'];
	$_SESSION['email'] = $arFields['email'];

	$deafult = array(
		'applicant' => 'Заявитель является',
		'debtor' 	=> 'Должник является',
		'name' 		=> 'Имя',
		'INN' 		=> 'ИНН',
		'phone' 	=> 'Телефон',
		'email' 	=> 'E-mail'
	);

	$email_to = "beatbox787@gmail.com";

	if(sendMail($email_to, $deafult, $arFields)){	
		echo "1";
	}else{
		echo "0";
	}

}else{
	echo "0";
}

?>