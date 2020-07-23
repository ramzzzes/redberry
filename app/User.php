<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function register($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first(),400);
        }
    }

    public function login($data)
    {
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first(),400);
        }

        if(!Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ])){
            throw new \Exception('Invalid Credentials',400);
        }

        return Auth::user()->createToken('redberry');

    }
}
