<?php

namespace Mealz\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected function createSuccess($entity) {
        $response = response('', 201);
        if (method_exists($entity, 'getResourceUrl')) {
           $response->header("Location", $entity->getResourceUrl());
        }
        return $response;
    }

    protected function updateSuccess() {
        return response('', 204);
    }
}
