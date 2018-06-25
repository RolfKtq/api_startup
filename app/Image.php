<?php

namespace App;

use App\Kandidat;
use App\Stoff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'images';
    protected $fillable = [
        'id',
        'image',
        'width',
        'height',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function kandidat()
    {
        return $this->belongsTo(Kandidat::class);
    }

    public function stoffer()
    {
        return $this->belongsToMany(Stoff::class);
    }

}
