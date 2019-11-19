<?php

namespace App\Http\Controllers;

use App\Ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RankingController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials');
    }

    public function index()
    {

    }

    public function store(Request $request)
    {
        $actions = ['like', 'dont_like'];

        $rules = [
            'action' => 'required',
            'hero_id' => 'required|integer|exists:heroes,id'
        ];

        Validator::validate($request->all(), $rules);
        $action = $request->action;
        $server = $request->server();
        $ip = $server['REMOTE_ADDR'];

        if(!in_array($action, $actions)){
            return $this->errorResponse('The action is not defined.', 400);
        }

        $where = [
            'ip_address' => $ip, 
            'hero_id' => $request->hero_id,
        ];
        
        Ranking::where($where)->delete();
        
        $ranking = new Ranking();

        $ranking->fill(array_merge($where, [
            $action => 1,
            'created_at' => date('Y-m-d H:i:s')
        ]));
        
        if(!$ranking->save()){
            return $this->errorResponse('Oops, an error occurred, try again later.', 400);
        }

        return $this->successResponse(true, 200);
    }
}