<?php

namespace App\Traits;

trait ToggleStatusTrait
{
    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->status === true) {
                $model->status = 'Active';
            } elseif ($model->status === false) {
                $model->status = 'Inactive';
            }
        });
    }
}
