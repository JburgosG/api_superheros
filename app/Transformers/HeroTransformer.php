<?php

namespace App\Transformers;

use App\Hero;
use League\Fractal\TransformerAbstract;

class HeroTransformer extends TransformerAbstract
{
/**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Hero $hero)
    {
        return [
            'id' => $hero->id,
            'name' => $hero->name,
            'picture' => $hero->picture,
            'info' => $hero->description,
            'publisher' => $hero->publisher
        ];
    }
}