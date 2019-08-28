<?php

require_once("mail.php");
session_start();

function getNewId(){
	$id = intval(file_get_contents("id.txt"));
	$id ++;
	file_put_contents("id.txt", $id);
	return $id;
}

if( !isset($_SESSION["id"]) || empty($_SESSION["id"]) ){
	$_SESSION["id"] = getNewId();
}

if( !isset($_SESSION["email"]) || !isset($_SESSION["phone"]) || !isset($_SESSION["price"]) ){
	echo "Не переданы параметры";
	die();
}

$_SESSION["type"] = $_REQUEST['type'];

switch ($_REQUEST['type']) {
	case 'card':
		require_once "tinkoff/TinkoffMerchantAPI.php";

		$name = (!empty($_SESSION["name"]))?$_SESSION["name"]:$_SESSION["INN"];
		$email = $_SESSION["email"];
		$phone = $_SESSION["phone"];
		$amount = intval($_SESSION["price"]);
		$orderId = $_SESSION["id"];

		$amount *= 100;

		$api = new TinkoffMerchantAPI(
		    '1565694994500DEMO',  //Ваш Terminal_Key
		    'hqoj67omeyy7q3z5'   //Ваш Secret_Key
		);

		$receipt = [
		    'Email'        => $email,
		    'Phone'        => $phone,
		    'Taxation'     => 'usn_income',
		    'Description'  => 'Публикация объявления о намерении обратиться в суд с заявлением о банкротстве?',
		    'Items'        => [
		        [
		            'Name'          => 'Публикация объявления о намерении обратиться в суд с заявлением о банкротстве?',
		            'Price'         => $amount,
		            'Quantity'      => 1.0,
		            'Amount'        => $amount,
		            'PaymentMethod' => 'full_payment',
		            'PaymentObject' => 'service',
		            'Tax'           => 'none'
		        ]
		    ],
		];

		$params = [
		    'OrderId' => $orderId,
		    'Amount'  => $amount,
		    'DATA'    => [
		        'Phone'        => $phone,
		        'Email'        => $email,
		        'Name'         => $name,
		    ],
		    'Receipt' => $receipt
		];

		$api->init($params);

		if( $api->error ){
			switch ($api->errorCode) {
				case '8':
					header("Location: /thanks/");
					break;
				default:
					header("Location: /error/");
					break;
			}
		}else{
			header("Location: ".$api->paymentUrl);
		    // var_dump($api->paymentUrl);
		    // var_dump($api->paymentId);
		    // var_dump($api->status);
		}
		break;
	case 'account':
		$deafult = array(
			'applicant' => 'Заявитель является',
			'debtor' 	=> 'Должник является',
			'name' 		=> 'Имя',
			'INN' 		=> 'ИНН',
			'phone' 	=> 'Телефон',
			'email' 	=> 'E-mail'
		);
		//Письмо админу
		$arFields = array();
		$arFields['applicant'] = $_SESSION['applicant'];
		$arFields['debtor'] = $_SESSION['debtor'];
		$arFields['name'] = $_SESSION['name'];
		$arFields['INN'] = $_SESSION['INN'];
		$arFields['phone'] = $_SESSION['phone'];
		$arFields['email'] = $_SESSION['email'];
		sendMail($deafult, $arFields);

		//Письмо клиенту
		$email_to = $_SESSION['email'];
		$subject = "Заявка на публикацию";
		$title = "Заявка на публикацию создана";
		$text = "Заявка на публикацию № ".$_SESSION['email']." успешно создана.";
		sendMailForClient($email_to, $subject, $title, $text);

		header("Location: /thanks/");
		break;
	default:
		header("Location: /");
		break;
}
?>