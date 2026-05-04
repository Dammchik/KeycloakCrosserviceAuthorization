<?php

namespace App\Http\Controllers\RestTraits;

use Illuminate\Http\JsonResponse;

trait DefaultDestroyTrait
{
    public function destroyValidation(): void
    {
    }

    /**
     * @param $model
     *
     * @return void
     */
    public function beforeDestroy($model): void
    {
    }

    public function afterDestroy($model): void
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(): JsonResponse
    {
        return $this->oldBaseActionWithModel(
            action: function ($model) {
                $this->destroyValidation();
                $this->beforeDestroy($model);
                $cloneModel = clone $model;
                $model->delete();
                $this->afterDestroy($cloneModel);

                return null;
            },
            actionName: 'destroy',
            code: 204
        );
    }
}
