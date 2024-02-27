<?php

namespace App\Jobs;

use App\Models\ProspectModel;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportProspectChunk implements ShouldQueue
{
  use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  public $uniqueFor = 3600;

  /**
   * Create a new job instance.
   */
  public function __construct(public $chunk)
  {
    //
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    \Log::info('ENTERED');
    $this->chunk->each(function (array $row) {
      ProspectModel::create([
        'first_name' => $row['first_name'],
        'last_name' => $row['last_name'],
        'email' => $row['email'],
      ]);
      // Model::withoutTimestamps(fn () => );
    });
    \Log::info('ENTEREDDDDD', ['batche' => $this->batch()->progress()]);
  }

  public function uniqueId(): string
  {
    return str()->uuid()->toString();
  }
}
