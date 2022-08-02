<?php

class PhonePrettier
{

  public $phone_number;
  public $options = [
    'mask' => '3'
  ];

  function __construct($options = [])
  {

    if (count($options)) {
      $this->options = array_replace_recursive($this->options, $options);
    }

  }

  public function get($phone_number)
  {

    $this->phone_number = trim($phone_number);

    return $this->prettify();

  }

  private function prettify()
  {

    $phone_number = preg_replace("/[^0-9]/", "", $this->phone_number);

    $len = strlen($phone_number);

    if ($len >= 9 && $len <= 13) {
      if ($len == 9) {
      // $phone_number = '0'.$phone_number;
      }
      $numbers = substr($phone_number, -9);
      switch ($this->options['mask']) {
        case '1':
          $phone_number = '0' . $numbers;
          break;
        case '2':
          $phone_number = '380' . $numbers;
          break;
        case '3':
          $phone_number = '+380' . $numbers;
          break;
        default:
          # code...
          break;
      }
    }

    return $phone_number;

  }

}

?>