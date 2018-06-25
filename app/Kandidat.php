<?php

namespace App;

use App\CV_post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Kandidat extends Model
{
   	use SoftDeletes;
	 	protected $dates = ['deleted_at'];
		protected $table = 'kandidater';
	
	
	 protected $fillable = [  
    'fornavn',
    'etternavn',
    'adresse',
    'postnummer',
    'poststed',
    'fodselsaar',
    'mail', 
		'telefon',
    'image_id',
    'nasjonalitet',
    'flyplass',
		'primaerkompetanse',
    'dawinci',
    'tekst',
	];
	
	protected $hidden = [	  
	'created_at', 
	'updated_at',
	'deleted_at',
   ];	
	    public function CV_poster()
    {
  			return $this->hasMany(CV_post::class);
    }
}

