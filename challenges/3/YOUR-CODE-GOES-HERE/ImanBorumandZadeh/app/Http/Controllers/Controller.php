<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * api response
     *
     * @param mixed $data
     * @param int   $status
     * @return JsonResponse
     */
    protected function apiResponse( mixed $data, int $status = 200) : JsonResponse
    {
        return response()
            ->json($data , $status);
    }
}
