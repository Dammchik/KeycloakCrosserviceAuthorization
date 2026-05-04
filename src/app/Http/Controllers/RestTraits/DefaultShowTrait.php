<?php

namespace App\Http\Controllers\RestTraits;

use Illuminate\Http\JsonResponse;

trait DefaultShowTrait
{

    /**
     * Show the specified resource.
     */
    public function show(): JsonResponse
    {
        return $this->oldBaseActionWithModel(actionName: 'show');
    }
}
