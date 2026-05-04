<?php

namespace App\Http\Controllers\RestTraits;

use Illuminate\Http\JsonResponse;

trait DefaultUpdateTrait
{

    /**
     * Update the specified resource in storage.
     */
    public function update(): JsonResponse
    {
        return $this->baseUpdateAction(validate: false);
    }
}
