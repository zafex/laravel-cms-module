<?php

namespace Apiex\Observers;

/**
 * @package zafex/apiexlara
 *
 * @author Fajrul Akbar Zuhdi <fajrulaz@gmail.com>
 *
 * @link https://github.com/zafex
 */

use Apiex\Entities\Audit;
use Apiex\Entities\AuditDetail;
use Illuminate\Database\Eloquent\Model;

class AuditLog
{
    /**
     * @param Model $model
     */
    public function created(Model $model)
    {
        app('LogCreation')->make('CREATE', $model, function (Audit $audit, Model $model) {
            foreach ($model->getAttributes() as $key => $value) {
                $detail = new AuditDetail;
                $detail->audit_id = $audit->getKey();
                $detail->field = $key;
                $detail->new_value = $value;
                $detail->old_value = '';
                $detail->save();
            }
        });
    }

    /**
     * @param Model $model
     */
    public function deleted(Model $model)
    {
        app('LogCreation')->make('DELETE', $model, function (Audit $audit, Model $model) {
            foreach ($model->getAttributes() as $key => $value) {
                $detail = new AuditDetail;
                $detail->audit_id = $audit->getKey();
                $detail->field = $key;
                $detail->old_value = $value;
                $detail->new_value = '';
                $detail->save();
            }
        });
    }

    /**
     * @param Model $model
     */
    public function updated(Model $model)
    {
        app('LogCreation')->make('UPDATE', $model, function (Audit $audit, Model $model) {
            $origins = $model->getOriginal();
            foreach ($model->getAttributes() as $key => $value) {
                if (!array_key_exists($key, $origins) || $value != $origins[$key]) {
                    $detail = new AuditDetail;
                    $detail->audit_id = $audit->getKey();
                    $detail->field = $key;
                    $detail->old_value = array_key_exists($key, $origins) ? $origins[$key] : '';
                    $detail->new_value = $value;
                    $detail->save();
                }
            }
        });
    }
}
