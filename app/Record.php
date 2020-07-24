<?php

namespace App;

use App\Gateway\Nutritionix;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Record extends Model
{
    use SoftDeletes;

    protected $table = 'record';

    protected $fillable = [
        'date', 'time', 'calories','text','user_id'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];

    public function validate($data)
    {
        $validator = Validator::make($data, [
            'text' => 'required',
            'calories' => 'integer',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i:s',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first(),400);
        }
    }


    public function store($data)
    {
        $this->fill([
            'user_id' => Auth::id(),
            'text' => $data['text'],
            'date' => $data['date'],
            'time' => $data['time'],
            'calories' => isset($data['calories']) ? $data['calories'] : false,
        ])->save();

        return $this;
    }

    public function setCaloriesAttribute($value)
    {
        if(!$value){ // if calories field empty calculate it via API
            $nutritionix = new Nutritionix();
            $value = $nutritionix->calculate($this->attributes['text']);
        }

        $this->attributes['calories'] = $value;
    }

    public function getCaloriesAttribute($value)
    {
        return (double)($value);
    }

}
