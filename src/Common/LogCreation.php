<?php

namespace Apiex\Common;

use Apiex\Entities\Audit;
use Apiex\Entities\User;
use Closure;
use Exception;
use Illuminate\Contracts\Routing\UrlGenerator as Url;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class LogCreation
{
    /**
     * @var mixed
     */
    protected $auth;

    /**
     * @var mixed
     */
    protected $request;

    /**
     * @var mixed
     */
    protected $url;

    /**
     * @param JWTAuth $auth
     */
    public function __construct(JWTAuth $auth, Request $request, Url $url)
    {
        $this->url = $url;
        $this->auth = $auth;
        $this->request = $request;
    }

    /**
     * @param $action
     * @param Model     $model
     */
    public function make($action = 'CREATE', Model $model, Closure $handler = null)
    {
        try {
            $user_id = 0;

            if (($model instanceof User) && !in_array($action, ['CREATE', 'UPDATE', 'DELETE'])) {
                $user_id = $model->id;
            }

            if ($this->auth->check()) {
                if ($token = $this->auth->parseToken()) {
                    $user_id = $token->getPayload()->get('sub');
                }
            }

            $audit = new Audit;
            $audit->user_id = $user_id;
            $audit->referer = $this->url->full();
            $audit->model = get_class($model);
            $audit->model_id = $model->getKey();
            $audit->browser = $this->request->header('User-Agent');
            $audit->ip = $this->request->ip();
            $audit->action = $action;
            if ($audit->save()) {
                return is_callable($handler) ? $handler($audit, $model) : $audit;
            }

        } catch (Exception $e) {
            // do nothing
            exit($e->getMessage());
        }
    }
}
