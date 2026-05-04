<?php

namespace App\Http\Controllers\RestTraits;

use Illuminate\Http\JsonResponse;

trait DefaultStoreTrait
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        return $this->baseStoreAction(validate: false);
    }
}
