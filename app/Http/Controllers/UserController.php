<?php

namespace App\Http\Controllers;

class UserController extends ApiController
{
    public function __construct()
    {
        $public = [];

        $this->middleware('auth:api')->except($public);
        $this->middleware('client.credentials')->only($public);
    }
}