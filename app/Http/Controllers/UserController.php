<?php

namespace App\Http\Controllers;

use Auth;
use Image;
use App\User;
use App\SocialNetwork;
use App\SocialNetworkUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    public function __construct()
    {
        $public = [];
        $premium = [];

        $this->middleware('auth:api')->except($public);
        $this->middleware('scope:all')->only($premium);
        $this->middleware('client.credentials')->only($public);
    }
}