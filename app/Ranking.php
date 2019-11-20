<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\RankingTransformer;

class Ranking extends Model
{
    public $timestamps = false;

    public $transformer = RankingTransformer::class;

    protected $fillable = [
        'likes',
        'hero_id',
        'dont_likes',
        'ip_address',
        'created_at'
    ];

    public function hero()
    {
        return $this->belongsTo('App\Hero');
    }

    public function scopeGetRanking($query)
    {
        $query->select('hero_id', DB::raw('sum(likes) likes'), DB::raw('sum(dont_likes) dont_likes'));
        $query->groupBy('hero_id');

        return $query;
    }
}