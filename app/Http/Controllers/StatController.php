<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StatController extends Controller
{
    public function set(Request $request)
    {
        if (!$request->has('code')) {
            throw new BadRequestHttpException();
        }

        Redis::incr($request->get('code'));
    }

    public function get()
    {
        $codes = ['ru', 'en'];

        return array_combine($codes, Redis::mget($codes));
    }
}
