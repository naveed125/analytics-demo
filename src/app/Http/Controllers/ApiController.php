<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    /**
     * Handler for /api/action/{param}
     * @param string $name
     * @return array
     */
    public function action(string $param) {
        return [
            "action" => $param
        ];
    }
}
