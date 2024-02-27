<?php

namespace App\Services;

use App\DTO\CsvDataDTO;
use App\Models\ProspectModel;

class CsvService
{
  public function import(CsvDataDTO $dto)
  {
    // Example: Perform additional processing or validation before saving
    $data = $dto->toArray();

    // Example: Save to the database
    ProspectModel::create($data);
  }
}
