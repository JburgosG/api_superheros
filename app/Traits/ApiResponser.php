<?php 

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

trait ApiResponser
{
    protected function successResponse($data, $code)
    {
        return Response::json($data, $code);
    }

    protected function errorResponse($message, $code)
    {
        return Response::json([
            'error' => $message, 
            'code' => $code
        ], $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        if($collection->isEmpty()){
            return $this->successResponse(['data' => $collection], $code);
        }        
        
        $transformer = $collection->first()->transformer;
        $collection = $this->transformData($collection, $transformer);
        return $this->successResponse($collection, $code);
    }

    protected function showOne(Model $instance, $code = 200)
    {
        $transformer = $instance->transformer;        
        $data = $this->transformData($instance, $transformer);

        return $this->successResponse($data, $code);
    }

    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);
        return $transformation->toArray();
    }
    
    protected function paginate($data)
    {
        $options = $this->getConfigPage();
        return $data->offset($options->start)->limit($options->perPage);
    }

    protected function getConfigPage()
    {
        $start = 0;
        $page = $this->page();        
        $perPage = $this->perPage();
        $start = ($page - 1) * $perPage;

        return (object) [
            'perPage' => $perPage,
            'start' => $start
        ];
    }

    public function getOptionsPaginate($results)
	{
		return [
			'hasMorePages' => $results->hasMorePages(),
			'currentPage' => $results->currentPage(),			
			'firstItem' => $results->firstItem(),
			'lastItem' => $results->lastItem(),
			'lastPage' => $results->lastPage(),
			'perPage' => $results->perPage(),
			'total' => $results->total(),
		];
	}

    protected function page()
    {   
        $page = 1;

        if(request()->has('page')){
            $page = (int) request()->page;
        }

        return $page;
    }

    protected function perPage()
    {
        $perPage = 15;
        $rules = ['per_page' => 'integer|min:2|max:50'];        
        Validator::validate(request()->all(), $rules);        

        if(request()->has('per_page')){
            $perPage = (int) request()->per_page;
        }

        return $perPage;
    }
}