<?php

namespace App\Http\Controllers;

use App\Hero;

class HeroController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials');
    }

    public function index()
    {
        $heroes = $this->paginate(Hero::orderBy('name'))->get();
        return $this->showAll($heroes);
    }

    public function show(Hero $hero)
    {
        return $this->showOne($hero);
    }
}