<?php

namespace App;

use App\Transformers\HeroTransformer;
use Illuminate\Database\Eloquent\Model;

class Hero extends Model
{
    public $timestamps = true;

    public $transformer = HeroTransformer::class;

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
