<?php

namespace App\Jobs;

use App\Models\ProspectModel;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\SimpleExcel\SimpleExcelReader;

class ProcessCsvDataJob implements ShouldQueue
{
  use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * The number of seconds after which the job's unique lock will be released.
   *
   * @var int
   */
  public $uniqueFor = 3600;

  protected $path;

  public function __construct($path)
  {
    \Log::info('kekeek', ['path' => $path]);
    $this->path = $path;
  }

  public function handle()
  {
    $row = $this->path;
    ProspectModel::create([
      'first_name' => $row['first_name'] ?? '',
      'last_name' => $row['last_name'] ?? '',
      'email' => $row['email'] ?? '',
    ]);

    // $reader = SimpleExcelReader::create(storage_path("app/{$this->path}"));
    // $test = $reader
    //   ->headersToSnakeCase()
    //   ->getRows()
    //   ->each(function ($row) {
    //     \Log::info('row', $row);
    //     ProspectModel::create([
    //       'first_name' => $row['first_name'] ?? '',
    //       'last_name' => $row['last_name'] ?? '',
    //       'email' => $row['email'] ?? '',
    //     ]);
    //   });
    // ->chunk(1000)
    // ->each(fn ($chunk) => ImportProspectChunk::dispatch($chunk));


    // $reader->headersToSnakeCase()->getRows()->each(function ($row) {
    //   \Log::info('row', $row);
    //   ProspectModel::create([
    //     'first_name' => $row['first_name'] ?? '',
    //     'last_name' => $row['last_name'] ?? '',
    //     'email' => $row['email'] ?? '',
    //   ]);
    // });
    \Log::info('success');


    // \Log::info('hehehe', ['path' => $this->path]);
    // $rows = SimpleExcelReader::create($this->path, 'csv')
    //   ->useDelimiter(',')
    //   ->headersToSnakeCase()
    //   ->getRows()
    //   ->chunk(1000)
    //   ->each(function ($row) {
    //     \Log::info('test', $row);
    //     // \DB::table('prospects')->insert([
    //     //   'first_name' => $row['first_name'],
    //     //   'last_name' => $row['last_name'],
    //     //   'email' => $row['email'],
    //     // ]);
    //     // ProspectModel::create([
    //     //   'first_name' => $row['first_name'],
    //     //   'last_name' => $row['last_name'],
    //     //   'email' => $row['email'],
    //     // ]);
    //   });
    // \Log::info('hehehe1', ['rows' => $rows]);
  }

  public function uniqueId(): string
  {
    return str()->uuid()->toString();
  }
}
