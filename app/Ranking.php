<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    public $timestamps = false;

    public $transformer = RankingTransformer::class;

    protected $fillable = [
        'like',
        'hero_id',
        'dont_like',
        'ip_address',
        'created_at'
    ];

    public function hero()
    {
        return $this->belongsTo('App\Hero');
    }

    public function scopeGetRanking($query)
    {

    }
}