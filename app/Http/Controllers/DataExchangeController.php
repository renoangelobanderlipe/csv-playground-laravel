<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCsvDataJob;
use App\Models\ProspectModel;
use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelReader;

use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class DataExchangeController extends Controller
{
  public function import(Request $request)
  {
    try {
      $request->validate([
        'prospects' => 'required|file|mimes:csv,txt|max:5000'
      ]);

      $uploadedFile = $request->file('prospects');
      $path = $uploadedFile->store('uploads');


      if ($uploadedFile->isValid()) {

        // $filePath = $uploadedFile->path();
        // Example: Store the file on disk
        // SimpleExcelReader::create($uploadedFile, 'csv')
        //   ->useDelimiter(',')
        //   ->headersToSnakeCase()
        //   ->getRows()
        //   ->each(function ($rows) {
        //     // ProspectModel::updateOrCreate([
        //     //   'first_name' => $row['first_name'],
        //     //   'last_name' => $row['last_name'],
        //     //   'email' => $row['email'],
        //     // ]);
        //   });


        $rows = [];
        $reader = SimpleExcelReader::create(storage_path("app/{$path}"))->getRows();

        $data = [];
        $jobs = [];

        foreach ($reader as $row) {
          $jobs[] = new ProcessCsvDataJob($row);
        }
        $batch = Bus::batch($jobs)->dispatch();
        // $test = $reader
        //   ->headersToSnakeCase()
        //   ->getRows()
        //   ->each(function ($row) use ($rows) {
        //     $rows[] = $row;
        //     // \Log::info('row', $row);
        //     // ProspectModel::create([
        //     //   'first_name' => $row['first_name'] ?? '',
        //     //   'last_name' => $row['last_name'] ?? '',
        //     //   'email' => $row['email'] ?? '',
        //     // ]);
        //   });
        // $batch = Bus::batch(ProcessCsvDataJob::dispatch($data))
        //   ->then(function (Batch $batch) {
        //     dump('success');
        //     \Log::info('Done Loggine');
        //   })
        //   // ->progress(function (Batch $batch) {
        //   //   \Log::info('job progress', ['prog' => $batch->progress()]);
        //   // })
        //   ->name('Prospects Import')
        //   ->dispatch();



        // $batch = Bus::batch([
        //   ProcessCsvDataJob::dispatch($path)
        // ])->before(function (Batch $batch) {
        //   dump('before', $batch);
        // })->progress(function (Batch $batch) {
        //   dump('progress', $batch);
        //   dump('// A single job has completed successfully...');
        // })->then(function (Batch $batch) {
        //   dump('then', $batch);
        //   // All jobs completed successfully...
        // })->catch(function (Batch $batch, Throwable $e) {
        //   dump('catch', $batch);
        //   // First batch job failure detected...
        // })->finally(function (Batch $batch) {
        //   // The batch has finished executing...
        // })->dispatch();

        return $batch->id;

        // SimpleExcelReader::create($uploadedFile, 'csv')
        //   ->useDelimiter(',')
        //   ->headersToSnakeCase()
        //   ->getRows()
        //   ->chunk(1000)
        //   ->each(function ($rows) {

        //     // dd('row', $rows);
        //     // ProspectModel::create([
        //     //   'first_name' => $row['first_name'],
        //     //   'last_name' => $row['last_name'],
        //     //   'email' => $row['email'],
        //     // ]);

        //   });
      }

      return response()->json(['message' => 'Success']);
    } catch (\Throwable $throwable) {
      return response()->json(['messages' => $throwable->getMessage()], 500);
    }
  }

  public function tester(Request $request)
  {
    $batch = null;

    if ($request->batch_id) {
      $batch = Bus::findBatch($request->batch_id);
    }

    return [
      'processedjob' => $batch->processedJobs(),
      'totalJobs' => $batch->totalJobs,
      'batch' => $batch,
      'percentage' => "{$batch->progress()} %",
    ];
  }
}
