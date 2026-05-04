<?php

namespace Modules\TestService\Http\Controllers;

use Illuminate\Routing\Controller;

class TestController extends Controller
{
    public function hello()
    {
        return response()->json([
            'message' => 'Hello from TestService'
        ]);
    }
}
