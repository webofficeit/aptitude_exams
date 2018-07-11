<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Backpack\CRUD\CrudTrait;

class User extends Authenticatable
{
    use Notifiable;
    use CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'male', 'mobile', 'exam_type_id', 'suspended'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function getSuspendedStatus()
    {
        return $this->suspended ? 'Yes' : 'No';
    }

    public function getGender()
    {
        return $this->male ? 'Yes' : 'No';
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function examType()
    {
        return $this->belongsTo(ExamType::class, 'exam_type_id');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function sendSms($message, $numbers)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(

            CURLOPT_RETURNTRANSFER => 1,

            CURLOPT_URL => "http://msg.smsspot.in/api/sms/format/json",

            CURLOPT_POST => 1,

            CURLOPT_CUSTOMREQUEST => 'POST',

            CURLOPT_HTTPHEADER => array('X-Authentication-Key: 02c1657766dc66965bc58a264d5442be', 'X-Api-Method:MT'),

            CURLOPT_POSTFIELDS => array(

                'mobile' => implode(',', $numbers),

                'route' => 'TL',

                'text' => urlencode($message),

                'sender' => 'APTTVM')
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return ($response);
    }
}
