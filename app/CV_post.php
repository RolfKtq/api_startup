<?php

namespace App;

use App\Kandidat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CV_post extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'cv_poster';

    protected $fillable = [
        'fra_dato',
        'til_dato',
        'kandidat_id',
        'image_id',
        'type_post',
        'tittel',
        'beskrivelse',
        'arrangor',
        'arbeidsgiver',
        'utskrift',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function kandidat()
    {
        return $this->hasOne(Kandidat::class);
    }

    public function getFraDatoAttribute($value)
    {
        if (strlen($value) != 0) {
            $norsk = explode("-", $value);
            return $norsk[2] . "-" . $norsk[1] . "-" . $norsk[0];
        }
    }

    public function getTilDatoAttribute($value)
    {
        if (strlen($value) != 0) {
            $norsk = explode("-", $value);
            return $norsk[2] . "-" . $norsk[1] . "-" . $norsk[0];
        }
    }

    public function setFraDatoAttribute($value)
    {
        if (strlen($value) != 0) {
            $norsk = explode("-", $value);
            $norsk[2] . '-' . $norsk[1] . '-' . $norsk[0];
            $this->attributes['fra_dato'] = $norsk[2] . '-' . $norsk[1] . '-' . $norsk[0];
        }
    }

    public function setTilDatoAttribute($value)
    {
        if (strlen($value) != 0) {
            $norsk = explode("-", $value);
            $norsk[2] . '-' . $norsk[1] . '-' . $norsk[0];
            $this->attributes['til_dato'] = $norsk[2] . '-' . $norsk[1] . '-' . $norsk[0];
        }
    }

}
