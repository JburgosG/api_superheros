<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;

/*
 * @author      Jairo Burgos Guarin
 * @package     Laravel 5.4
 * @subpackage  Api Controller
 * @category    Services
 */
class ApiController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        $this->middleware('auth:api');
    }
}