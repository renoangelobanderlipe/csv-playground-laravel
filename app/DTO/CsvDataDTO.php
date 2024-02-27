<?php

namespace App\DTO;

class CsvDataDTO
{
  public $first_name;
  public $last_name;
  public $email;

  public function __construct($first_name, $last_name, $email)
  {
    $this->first_name = $first_name;
    $this->last_name = $last_name;
    $this->email = $email;
  }
}
