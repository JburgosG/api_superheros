<?php 

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/*
 * @author      Jairo Burgos Guarin
 * @package     Laravel 5.4
 * @subpackage  ApiResponser
 * @category    Traits
 */
trait ApiResponser
{
    /*
    * @name         successResponse
    * @return       json response
    * @description  Respondemos con los datos en formato JSON
    */
    protected function successResponse($data, $code)
    {
        return Response::json($data, $code);
    }

    /*
    * @name         errorResponse
    * @return       json response
    * @description  Respondemos error en formato JSON, presentada alguna excepción.
    */
    protected function errorResponse($message, $code)
    {
        return Response::json([
            'error' => $message, 
            'code' => $code
        ], $code);
    }

    /*
    * @name         showAll
    * @return       json response
    * @description  Respondemos con una colección de datos (lista)
    */
    protected function showAll(Collection $collection, $code = 200)
    {
        if($collection->isEmpty()){
            return $this->successResponse(['data' => $collection], $code);
        }        
        
        $transformer = $collection->first()->transformer;
        $collection = $this->transformData($collection, $transformer);
        return $this->successResponse($collection, $code);
    }

    /*
    * @name         showOne
    * @return       json response
    * @description  Respondemos con una colección de datos (uno)
    */
    protected function showOne(Model $instance, $code = 200)
    {
        $transformer = $instance->transformer;        
        $data = $this->transformData($instance, $transformer);

        return $this->successResponse($data, $code);
    }

    /*
    * @name         transformData
    * @return       json response
    * @description  Preparamos los datos que se le presentaran al usuario
    */
    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);
        return $transformation->toArray();
    }
    
    /*
    * @name         paginate
    * @return       Query Eloquent
    * @description  Limitamos consulta a partir de una paginación
    */
    protected function paginate($data)
    {
        $options = $this->getConfigPage();
        return $data->offset($options->start)->limit($options->perPage);
    }

    /*
    * @name         getConfigPage
    * @return       Object
    * @description  Parametros de configuración para el paginador
    */
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

    /*
    * @name         page
    * @return       Integer
    * @description  Página que solicita el usuario
    */
    protected function page()
    {   
        $page = 1;

        if(request()->has('page')){
            $page = (int) request()->page;
        }

        return $page;
    }

    /*
    * @name         perPage
    * @return       Integer
    * @description  Numero de registros por página
    */
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