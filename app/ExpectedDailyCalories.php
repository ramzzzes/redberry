<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpectedDailyCalories extends Model
{
    protected $table = 'expected_daily_calories';

    protected $fillable = [
        'date', 'calories','user_id'
    ];

    protected $hidden = [
        'updated_at'
    ];

    public function create($data)
    {
        $validator = Validator::make($data, [
            'calories' => 'required|integer',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first(),400);
        }

        return ExpectedDailyCalories::updateOrCreate([
            'user_id' => Auth::id(),
            'date' => $data['date']
        ],[
            'calories' => $data['calories']
        ]);
    }
}
