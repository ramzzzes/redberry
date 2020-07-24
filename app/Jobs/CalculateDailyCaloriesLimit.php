<?php

namespace App\Jobs;

use App\Record;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateDailyCaloriesLimit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $record;
    public $overdue = true;

    /**
     * Create a new job instance.
     *
     * @param $record
     */
    public function __construct($record)
    {
        $this->record = $record;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $totalReceivedCalories = Record::where('user_id',$this->record->user_id)
            ->where('date',$this->record->date)->with('expectedDailyCalories');


        if(!$this->record->expectedDailyCalories){
            $this->overdue = false;
        }


        if($totalReceivedCalories->sum('calories') < $this->record->expectedDailyCalories['calories']){
            $this->overdue = false;
        }

        $totalReceivedCalories->update([
            'overdue' => $this->overdue
        ]);


    }
}
