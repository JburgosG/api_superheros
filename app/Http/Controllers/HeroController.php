<?php

namespace App\Http\Controllers;

use App\Hero;
use Illuminate\Http\Request;

class HeroController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only('index');
        $this->middleware('auth:api')->except('index');
    }

    public function index()
    {
        return $this->showAll($this->paginate(Hero::orderBy('name'))->get());
    }
}