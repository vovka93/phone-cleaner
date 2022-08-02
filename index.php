<?php

require('./crest.php');
require('./prettier.php');

$prettier = new PhonePrettier();

$entities = [
  'contact',
  'company',
  'lead'
];

$params = [
  'order' => [
    "ID" => "DESC"
  ],
  'filter' => [
    "HAS_PHONE" => "Y"
  ],
  'select' => ["ID", "PHONE"]
];

foreach ($entities as $entity) {
  $listmethod = 'crm.' . $entity . '.list';
  $res = CRest::call($listmethod, $params);
  $steps = ceil($res['total'] / 50) * 50;
  for ($i = 0; $i < $steps; $i += 50) {
    $params['start'] = strval($i);
    $res = CRest::call($listmethod, $params);
    if(array_key_exists('result', $res)) {
      foreach ($res['result'] as $item) {
        $phones = [];
        foreach ($item['PHONE'] as $phone) {
          $new_phone = $phone;
          $new_phone['VALUE'] = $prettier->get($phone['VALUE']);
          array_push($phones, $new_phone);
        }
        $updatemethod = 'crm.' . $entity . '.update';
        $res = CRest::call($updatemethod, [
          'id' => $item['ID'],
          'fields' => [
            'PHONE' => $phones
          ]
        ]);
        usleep(500000);
      }
    }
  }
}
