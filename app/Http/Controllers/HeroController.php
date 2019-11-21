<?php

namespace App\Http\Controllers;

use App\Hero;

/*
 * @author      Jairo Burgos Guarin
 * @package     Laravel 5.4
 * @subpackage  Hero Controller
 * @category    Services
 */
class HeroController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials');
    }

    /*
    * @name         index
    * @return       collection
    * @description  Lista de Heroes
    */
    public function index()
    {
        $heroes = $this->paginate(Hero::orderBy('name'))->get();
        return $this->showAll($heroes);
    }

    /*
    * @name         show
    * @return       collection
    * @description  Ver un héroe especifico
    */
    public function show(Hero $hero)
    {
        return $this->showOne($hero);
    }
}