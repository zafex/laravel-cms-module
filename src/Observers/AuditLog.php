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
use Exception;
use Illuminate\Database\Eloquent\Model;

class AuditLog
{
    /**
     * @param Model $model
     */
    public function created(Model $model)
    {
        try {
            $audit = new Audit;
            $audit->user_id = auth()->check() ? auth()->user()->id : 0;
            $audit->referer = url()->full();
            $audit->model = get_class($model);
            $audit->model_id = $model->getKey();
            $audit->browser = request()->header('User-Agent');
            $audit->action = 'CREATE';
            if ($audit->save()) {
                foreach ($model->getAttributes() as $key => $value) {
                    $detail = new AuditDetail;
                    $detail->audit_id = $audit->getKey();
                    $detail->field = $key;
                    $detail->new_value = $value;
                    $detail->old_value = '';
                    $detail->save();
                }
            }
        } catch (Exception $e) {
            // do nothing
        }
    }

    /**
     * @param Model $model
     */
    public function deleted(Model $model)
    {
        try {
            $audit = new Audit;
            $audit->user_id = auth()->check() ? auth()->user()->id : 0;
            $audit->referer = url()->full();
            $audit->model = get_class($model);
            $audit->model_id = $model->getKey();
            $audit->browser = request()->header('User-Agent');
            $audit->action = 'DELETE';
            if ($audit->save()) {
                foreach ($model->getAttributes() as $key => $value) {
                    $detail = new AuditDetail;
                    $detail->audit_id = $audit->getKey();
                    $detail->field = $key;
                    $detail->old_value = $value;
                    $detail->new_value = '';
                    $detail->save();
                }
            }
        } catch (Exception $e) {
            // do nothing
        }
    }

    /**
     * @param Model $model
     */
    public function updated(Model $model)
    {
        try {
            $audit = new Audit;
            $audit->user_id = auth()->check() ? auth()->user()->id : 0;
            $audit->referer = url()->full();
            $audit->model = get_class($model);
            $audit->model_id = $model->getKey();
            $audit->browser = request()->header('User-Agent');
            $audit->action = 'UPDATE';
            if ($audit->save()) {
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
            }
        } catch (Exception $e) {
            // do nothing
        }
    }
}
