<?

require_once("../mail.php");
session_start();

if( isset($_GET["success"]) ){
	if( isset($_GET["type"]) ){
		$_SESSION["type"] = $_GET["type"];
	}

	if( $_GET["success"] == "false" ){
		$_SESSION["error"] = true;
	}else{
		unset($_SESSION["error"]);
		
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
	}
}

if( !isset($_SESSION["id"]) ){
	header("Location: /");
}

if( isset($_SESSION["error"]) ){
	header("Location: /error/");
}

?><!DOCTYPE html>
<html>
<head>
	<title>Оплата прошла успешно! Ваша заявка передана в работу</title>
	<meta name="keywords" content=''>
	<meta name="description" content=''>

	<meta name="viewport" content="width=device-width,minimum-scale=1,maximum-scale=1">
	<meta name="format-detection" content="telephone=no">

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="../css/reset.css" type="text/css">
	<link rel="stylesheet" href="../css/jquery.fancybox.css" type="text/css">
	<link rel="stylesheet" href="../css/KitAnimate.css" type="text/css">
	<link rel="stylesheet" href="../css/chosen.min.css" type="text/css">
	<link rel="stylesheet" href="../css/layout.css" type="text/css">

	<link rel="apple-touch-icon-precomposed" sizes="57x57" href="http://федресурс.рус/favicon/apple-touch-icon-57x57.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://федресурс.рус/favicon/apple-touch-icon-114x114.png" />
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://федресурс.рус/favicon/apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://федресурс.рус/favicon/apple-touch-icon-144x144.png" />
	<link rel="apple-touch-icon-precomposed" sizes="60x60" href="http://федресурс.рус/favicon/apple-touch-icon-60x60.png" />
	<link rel="apple-touch-icon-precomposed" sizes="120x120" href="http://федресурс.рус/favicon/apple-touch-icon-120x120.png" />
	<link rel="apple-touch-icon-precomposed" sizes="76x76" href="http://федресурс.рус/favicon/apple-touch-icon-76x76.png" />
	<link rel="apple-touch-icon-precomposed" sizes="152x152" href="http://федресурс.рус/favicon/apple-touch-icon-152x152.png" />
	<link rel="icon" type="image/png" href="http://федресурс.рус/favicon/favicon-196x196.png" sizes="196x196" />
	<link rel="icon" type="image/png" href="http://федресурс.рус/favicon/favicon-96x96.png" sizes="96x96" />
	<link rel="icon" type="image/png" href="http://федресурс.рус/favicon/favicon-32x32.png" sizes="32x32" />
	<link rel="icon" type="image/png" href="http://федресурс.рус/favicon/favicon-16x16.png" sizes="16x16" />
	<link rel="icon" type="image/png" href="http://федресурс.рус/favicon/favicon-128.png" sizes="128x128" />
	<meta name="application-name" content="Публикация объявления о банкротстве в Едином реестре"/>
	<meta name="msapplication-TileImage" content="http://федресурс.рус/favicon/mstile-144x144.png" />
	<meta name="msapplication-square70x70logo" content="http://федресурс.рус/favicon/mstile-70x70.png" />
	<meta name="msapplication-square150x150logo" content="http://федресурс.рус/favicon/mstile-150x150.png" />
	<meta name="msapplication-wide310x150logo" content="http://федресурс.рус/favicon/mstile-310x150.png" />
	<meta name="msapplication-square310x310logo" content="http://федресурс.рус/favicon/mstile-310x310.png" />

	<link rel="icon" type="image/vnd.microsoft.icon" href="../favicon.ico">
</head>
<body>
	<div class="b b-head b-head-thanks">
		<div class="b-head-light"></div>
		<div class="b-block">
			<div class="b-head-top">
				<a href="/" class="b-logo"></a>
				<div class="b-top-text">Сервис онлайн публикации объявлений о намерении обратиться в суд с заявлением о банкротстве</div>
				<div class="b-top-phone">
					<a href="tel:88002019968">8 (800) 201-99-68</a>
					<small>звонок бесплатный</small>
				</div>
			</div>
			<div class="b-head-content">
				<?if($_SESSION["type"] == "card"):?>
					<h1>Оплата прошла успешно! <br><b>Ваша заявка № <?=$_SESSION['id']?> передана в работу</b></h1>
					<div class="b-head-success card-info"><b>Мы в течение 3-х рабочих дней опубликуем объявление</b> и отправим ссылку на Ваш e-mail: <h3 class="email-client"><?=$_SESSION['email']?></h3></div>
				<?else:?>
					<h1><b>Заявка на публикацию № <?=$_SESSION['id']?></b> успешно создана</h1>
					<div class="b-head-success account-info"><b>В течение одого рабочего дня</b> мы отправим договор и счет на оплату услуги по опубликованию объявления на Ваш e-mail: <h3 class="email-client"><?=$_SESSION['email']?></h3></div>
				<?endif;?>
			</div>
		</div>
	</div>

	<div class="b b-6">
		<div class="b-block">
			<h2>Остались вопросы? Укажите, как с вами связаться и мы <b>бесплатно проконсультируем вас</b></h2>
			<form class="b-form-consultation" method="POST" action="/kitsend.php">
				<div class="b-input">
					<input type="text" name="name" required>
					<label>Контактное лицо <b class="required">*</b></label>
				</div>
				<div class="b-input">
					<input type="text" name="phone">
					<label>Телефон</label>
				</div>
				<div class="b-input">
					<input type="text" name="email">
					<label>E-mail</label>
				</div>
				<div class="warning">Укажите, пожалуйста, ваш телефон или e-mail</div>
				<a href="#" class="b-btn b-btn-submit ajax">Проконсультироваться</a>
				<a href="#b-popup-success-small" class="b-thanks-link fancy" style="display:none;"></a>
				<div class="b-politics">Отправляя форму, я даю согласие на обработку моих персональных данных в соответствии с <a href="politics.pdf" target="_blank">политикой конфиденциальности</a></div>
				<input type="hidden" name="subject" value="Новая заявка">
				<input type="submit" value="Отправить" style="display:none;">
			</form>
		</div>
	</div>

	<div class="b b-footer">
		<div class="b-footer-top">
			<div class="b-block">
				<div class="b-footer-col">
					<a href="/" class="b-footer-logo"></a>
					<div class="address-main with-icon icon-mark">127220, г.&nbsp;Москва, абонентский ящик №&nbsp;36</div>
				</div>
				<div class="b-footer-col b-footer-center">
					<div class="b-footer-text">Сервис онлайн публикации объявлений о&nbsp;намерении обратиться в арбитражный суд с&nbsp;заявлением о банкротстве</div>
				</div>
				<div class="b-footer-col">
					<div class="b-footer-phone">
						<a href="tel:88002019968">8 (800) 201-99-68</a>
						<small>звонок бесплатный</small>
					</div>
					<a href="mailto:info@m1.moscow" class="with-border">info@m1.moscow</a>
					<div class="address-mobile">127220, г.&nbsp;Москва, абонентский ящик №&nbsp;36</div>
				</div>
			</div>
		</div>
		<div class="b-footer-bottom">
			<div class="b-block clearfix">
				<div class="b-footer-bottom-left">
					<div class="b-footer-item b-copyright">© ООО «М1», 2019. Все права защищены.</div>
					<div class="b-footer-item">
						<a href="politics.pdf" target="_blank" class="with-border">Политика конфиденциальности</a>
					</div>
					<div class="b-footer-item">
						<a href="offer_m1.docx" target="_blank" class="with-border">Оферта</a>
					</div>
				</div>
				<div class="b-footer-bottom-right">
					<a href="#" class="b-footer-item b-tinkoff"></a>
					<div class="b-footer-item b-redder">
						<span>Разработка сайта:</span>
						<a href="http://redder.pro/" class="b-redder-logo" target="_blank"></a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div style="display:none;">
		<a href="#b-popup-error" class="b-error-link fancy" style="display:none;"></a>
		<div class="b-popup" id="b-popup-1">
			<h3>Оставьте заявку</h3>
			<h4>и наши специалисты<br>свяжутся с Вами в ближайшее время</h4>
			<form action="kitsend.php" data-goal="CALLBACK" method="POST" id="b-form-1">
				<div class="b-popup-form">
					<label for="name">Введите Ваше имя</label>
					<input type="text" id="name" name="name" required/>
					<label for="tel">Введите Ваш номер телефона</label>
					<input type="text" id="tel" name="phone" required/>
					<label for="tel">Введите Ваш E-mail</label>
					<input type="text" id="tel" name="email" required/>
					<input type="hidden" name="subject" value="Заказ"/>
					<input type="submit" style="display:none;">
					<a href="#" class="b-btn b-blue-btn ajax">Заказать</a>
					<a href="#b-popup-success" class="b-thanks-link fancy" style="display:none;"></a>
				</div>
			</form>
		</div>

		<div class="b-popup b-popup-request" id="b-popup-request">
			<h3>Оформление заявки на публикацию <b>уведомления о банкротстве</b></h3>
			<form id="b-form-request" class="b-form-request" action="kitsend.php" method="POST">
				<div class="b-select">
					<select name="applicant" class="select-chosen">
						<option>Должником 1</option>
						<option>Должником 2</option>
						<option>Должником 3</option>
					</select>
					<div class="note">Заявитель является</div>
				</div>
				<div class="b-select">
					<select name="debtor" class="select-chosen">
						<option value="physical">Физическим лицом</option>
						<option value="legal">Юридическим лицом</option>
						<option value="entrepreneur">Индивидуальным предпринимателем</option>
					</select>
					<div class="note">Должник является</div>
				</div>
				<div class="b-input b-input-name">
					<input type="text" name="name" required="">
					<label>ФИО должника <b class="required">*</b></label>
				</div>
				<div class="b-input b-input-INN" style="display: none;">
					<input type="text" name="INN" disabled="">
					<label>ИНН должника <b class="required">*</b></label>
				</div>
				<div class="b-input">
					<input type="text" name="phone" required="">
					<label>Ваш телефон <b class="required">*</b></label>
				</div>
				<div class="b-input">
					<input type="text" name="email" required="">
					<label>Ваш e-mail <b class="required">*</b></label>
				</div>
				<a href="#" class="b-btn b-btn-submit ajax">Отправить заявку<span class="mobile-hide"> на публикацию</span></a>
				<div class="b-politics">Отправляя форму, я даю согласие на обработку моих персональных данных в соответствии с <a href="politics.pdf" target="_blank">политикой конфиденциальности</a></div>
				<a href="#b-popup-success" class="b-thanks-link fancy" style="display:none;"></a>
				<input type="submit" value="Отправить" style="display:none;">
			</form>
		</div>

		<div class="b-popup-success b-popup" id="b-popup-success">
			<h3>Заявка на публикацию уведомления <b>успешно отправлена</b></h3>
			<p>Для начала процедуры размещения уведомления необходимо оплатить услугу в размере <b>1&nbsp;199&nbsp;руб.</b> Выберите удобный для вас способ оплаты:</p>
			<div class="b-radio">
				<input id="payment-card" type="radio" name="payment" value="card" checked>
				<label for="payment-card">Оплата банковской картой</label>
			</div>
			<div class="b-radio">
				<input id="payment-account" type="radio" name="payment" value="account">
				<label for="payment-account">Оплата на расчетный счет<small>На ваш e-mail будут отправлены договор <br>и счет на оплату</small></label>
			</div>
			<div class="b-payment-card">
				<a href="#" data-action="thanks.php" data-method="GET" class="b-btn b-btn-submit b-payment-card-btn ajax">Оплатить 1 199 руб.</a>
				<div class="b-offer">Производя оплату вы соглашаетесь с условиями <a href="offer.pdf" target="_blank">оферты</a></div>
			</div>
			<div class="b-payment-account" style="display: none;">
				<a href="#" data-action="thanks.php" data-method="GET" class="b-btn b-btn-submit b-payment-account-btn ajax">Получить счет на оплату</a>
			</div>
		</div>
		
		<div class="b-popup-success-small b-popup" id="b-popup-success-small">
			<h3>Спасибо! Ваша заявка <b>успешно отправлена</b></h3>
			<p>Наш менеджер свяжется с Вами в ближайшее время и ответит на все Ваши вопросы</p>
			<a href="#" class="b-btn b-btn-popup-close" onclick="$.fancybox.close(); return false;">Закрыть</a>
		</div>

		<div class="b-popup-error b-popup" id="b-popup-error">
			<h3><b>Ошибка отправки</b></h3>
			<p>Пожалуйста, попробуйте отправить Вашу заявку позже или позвоните нам по телефону: <a href="tel:88002019968"><b>8&nbsp;(800)&nbsp;201-99-68<b></a></p>
			<a href="#" class="b-btn b-btn-popup-close" onclick="$.fancybox.close(); return false;">Закрыть</a>
		</div>
	</div>
	<script type="text/javascript" src="../js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="../js/jquery.fancybox.min.js"></script>
	<script type="text/javascript" src="../js/jquery.touch.min.js"></script>
	<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
	<script type="text/javascript" src="../js/KitAnimate.js"></script>
	<? if( !(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')!==false || strpos($_SERVER['HTTP_USER_AGENT'],'rv:11.0')!==false) ): ?>
		<script type="text/javascript" src="../js/mask.js"></script>
	<? else: ?>
		<script type="text/javascript" src="../js/jquery.maskedinput.min.js"></script>
	<? endif; ?>
	<script type="text/javascript" src="../js/KitSend.js"></script>
	<script type="text/javascript" src="../js/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="../js/main.js"></script>
</body>
</html>