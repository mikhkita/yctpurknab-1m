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
		$orderId = md5($_SESSION["id"].time());

		// $amount = 1;

		$amount *= 100;

		$api = new TinkoffMerchantAPI(
		    '1565694994500', //Ваш Terminal_Key
		    '04kt65rwy5ihggm5' //Ваш Secret_Key
		);

		$receipt = [
		    'Email'        => $email,
		    'Phone'        => $phone,
		    'Taxation'     => 'usn_income',
		    'Description'  => 'Публикация объявления о намерении обратиться в суд с заявлением о банкротстве',
		    'Items'        => [
		        [
		            'Name'          => 'Публикация объявления о намерении обратиться в суд с заявлением о банкротстве',
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
		    'OrderId' => $_SESSION["id"],
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
		}
		break;
	case 'account':
		$deafult = array(
			'price'		=> 'Сумма',
			'applicant' => 'Заявитель является',
			'creditorINN' => 'ИНН кредитора',
			'debtor' 	=> 'Должник является',
			'name' 		=> 'Имя',
			'INN' 		=> 'ИНН',
			'phone' 	=> 'Телефон',
			'email' 	=> 'E-mail'
		);

		//Письмо админу
		$arFields = array();
		$arFields['price'] = $_SESSION["price"]." руб.";
		$arFields['applicant'] = $_SESSION['applicant'];
		if( !empty($_SESSION['creditorINN']) ){
			$arFields['creditorINN'] = $_SESSION['creditorINN'];
		}
		$arFields['debtor'] = $_SESSION['debtor'];

		if( !empty($_SESSION['name']) ){
			$arFields['name'] = $_SESSION['name'];
		}
		if( !empty($_SESSION['INN']) ){
			$arFields['INN'] = $_SESSION['INN'];
		}

		$arFields['phone'] = $_SESSION['phone'];
		$arFields['email'] = $_SESSION['email'];
		$arFields['subject'] = "Оплата на расчетный счет";
		sendMail($deafult, $arFields, true);

		//Письмо клиенту
		$email_to = $_SESSION['email'];
		$subject = "Заявка на публикацию объявления о банкротстве";
		$title = "Ваша заявка на публикацию объявления № ".$_SESSION['id']." успешно создана";
		$text = "В течение одого рабочего дня мы отправим договор и счет на оплату услуги по опубликованию объявления на Ваш e-mail";
		sendMailForClient($email_to, $subject, $title, $text);

		header("Location: /thanks/");
		break;
	default:
		header("Location: /");
		break;
}
?>