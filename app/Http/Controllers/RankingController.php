<?php

namespace App\Http\Controllers;

use App\Ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/*
 * @author      Jairo Burgos Guarin
 * @package     Laravel 5.4
 * @subpackage  Ranking Controller
 * @category    Services
 */
class RankingController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials');
    }

    /*
    * @name         index
    * @return       collection
    * @description  Lista de heroes con mayor numero de Me Gusta.
    */
    public function index()
    {
        $data = Ranking::GetRanking()->orderBy('likes', 'desc');
        $top = $this->paginate($data)->get();

        return $this->showAll($top);
    }

    /*
    * @name         store
    * @return       true, false or error message
    * @description  Almacenamos Me Gusta y No Me Gusta
    */
    public function store(Request $request)
    {
        $actions = ['add', 'remove'];
        $types = ['likes', 'dont_likes'];

        $rules = [
            'type' => 'required',
            'action' => 'required',
            'hero_id' => 'required|integer|exists:heroes,id'
        ];

        Validator::validate($request->all(), $rules);

        $type = $request->type;
        $action = $request->action;
        $server = $request->server();
        $ip = $server['REMOTE_ADDR'];

        if(!in_array($action, $actions) || !in_array($type, $types)){
            return $this->errorResponse('The action is not defined.', 400);
        }

        $where = [
            'ip_address' => $ip, 
            'hero_id' => $request->hero_id,
        ];
        
        Ranking::where($where)->delete();

        if($action == 'add'){
            $ranking = new Ranking();
    
            $ranking->fill(array_merge($where, [
                $type => 1,
                'created_at' => date('Y-m-d')
            ]));
            
            if(!$ranking->save()){
                return $this->errorResponse('Oops, an error occurred, try again later.', 400);
            }
        }

        return $this->successResponse(true, 200);
    }
}