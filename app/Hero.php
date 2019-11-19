<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hero extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'name',
        'picture',
        'description',
        'publisher_id'
    ];

    public function publisher()
    {
        return $this->belongsTo('App\Publisher');
    }
}
