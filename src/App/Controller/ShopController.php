<?php

namespace App\Controller;

use App\Repository\ItemsRepository as items;
use App\Repository\CartRepository as cart;

class ShopController extends Controller
{
    private $url_elements;
    private $params;
    
    private $response_code = 200;
    
    private $response_codes = [
        'OK' => 200,
        'BAD-REQUEST' => 400,
        'NOT-FOUND' => 404,
    ];
    
    public function ShopAction()
    {
        $result = null;
        
        $action = $_SERVER['REQUEST_METHOD'];
        $this->url_elements = explode('/', $_SERVER['PATH_INFO']);
        
        $this->params = json_decode($_REQUEST, true);
        
        if (method_exists($this, $action))
        {
            try{
                $result = $this->$action();
            }
            catch(\Exception $e)
            {
                $result = $e->getMessage();
                $this->response_code = $e->getCode();
            }
        }
        else
        {
            $this->response_code = $this->response_codes['BAD-REQUEST'];
        }
        
        http_response_code($this->response_code);
        
        return [$result, $responseCode];
    }

    private function put()
    {
        $class = New $this->url_elements[0]();
        return $class->$this->url_elements[1]($this->params);
    }
    
    private function post()
    {
        $class = New $this->url_elements[0]();
        return $class->$this->url_elements[1]($this->params); 
    }
    
    private function get()
    {
        $class = New $this->url_elements[0]();
        return $class->$this->url_elements[1]();
    }
}
