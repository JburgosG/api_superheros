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
        $perPage = $this->getConfigPage()->perPage;
        $data = Hero::orderBy('name')->paginate($perPage);
        $optionsPaginate = $this->getOptionsPaginate($data);

        foreach($data as $item){
			if(!empty($transformer = $item->transformer)){
				$metadata = $this->transformData($item, $transformer);
				$resp[] = $metadata['data'];
			}
		}

        $result = [
			'data' => $resp,
			'optionsPaginate' => $optionsPaginate
		];

        return $this->successResponse($result, 200);
    }
}