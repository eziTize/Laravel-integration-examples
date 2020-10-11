<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Twilio extends Model
{
    use HasFactory;

    protected $table = "twilios";
    protected $fillable = [
            'session_name', 'session_pass'
        ];

    /*
	|----------------------------------------------------------------
	|	Validation rules
	|----------------------------------------------------------------
	*/
	public $rules = array(

		'session_name'		=> 'required|max:191',
        'session_pass'		=> 'required|max:191',

    );

    /*
	|----------------------------------------------------------------
	|	Validate data records
	|----------------------------------------------------------------
	*/
    public function validate($data){
       
        $ruleType = $this->rules;

        $validator = Validator::make($data,$ruleType);

        if($validator->fails()){
            return $validator;
        }
	}
}
