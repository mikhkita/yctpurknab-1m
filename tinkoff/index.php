<?php

$email = 'test@test.com';
$emailCompany = 'testCompany@test.com';
$phone = '89179990000';

function __autoload($className)
{
    include $className . '.php';
}

$api = new TinkoffMerchantAPI(
    '1565694994500DEMO',  //Ваш Terminal_Key
    'hqoj67omeyy7q3z5'   //Ваш Secret_Key
);

$receipt = [
    'EmailCompany' => $emailCompany,
    'Email'        => $email,
    'Phone'        => $phone,
    'Taxation'     => 'usn_income',
    'Description'  => 'Публикация объявления о намерении обратиться в суд с заявлением о банкротстве?',
    'Items'        => [
        [
            'Name'          => 'Публикация объявления о намерении обратиться в суд с заявлением о банкротстве?',
            'Price'         => 1199,
            'Quantity'      => 1.0,
            'Amount'        => 1199,
            'PaymentMethod' => 'full_payment',
            'PaymentObject' => 'service',
            'Tax'           => 'none'
        ]
    ],
];

$params = [
    'OrderId' => 200001,
    'Amount'  => $amount,
    'DATA'    => [
        'Phone'        => $phone,
        'Email'           => $email,
        'Connection_type' => 'example'
    ],
    'Receipt' => $receipt
];

$api->init($params);

if( $api->error ){
    var_dump($api->error);
}else{
    var_dump($api->paymentUrl);

    var_dump($api->paymentId);

    var_dump($api->status);
}