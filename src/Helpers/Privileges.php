<?php

namespace Apiex\Helpers;

use Apiex\Entities;
use Exception;
use Illuminate\Contracts\Cache\Repository as CacheContract;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Support\Arr;

class Privileges
{
    /**
     * @var mixed
     */
    protected $cache;

    /**
     * @var mixed
     */
    protected $config;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var array
     */
    protected $myids = [];

    /**
     * @var mixed
     */
    protected $name;

    /**
     * @var array
     */
    protected $roles = [];

    /**
     * @var array
     */
    protected $users = [];

    /**
     * @param CacheContract  $cache
     * @param ConfigContract $config
     */
    public function __construct(CacheContract $cache, ConfigContract $config)
    {
        $this->cache = $cache;
        $this->config = $config;

        $this->name = $this->config->get('privilege_cache_name') ?: 'privileges';

        if (empty($this->items)) {
            if ($this->cache->has($this->name)) {
                $privileges = $this->cache->get($this->name) ?: [];
                $this->users = Arr::get($privileges, 'users', []);
                $this->roles = Arr::get($privileges, 'roles', []);
                $this->items = Arr::get($privileges, 'items', []);
                $this->myids = Arr::get($privileges, 'myids', []);
            } else {
                $roles = [];
                $permissions = [];
                $privileges = Entities\Privilege::all();
                $assignments = Entities\PrivilegeAssignment::all();
                $users = Entities\PrivilegeUser::all();
                $myids = Entities\UserPermission::all();
                foreach ($privileges as $privilege) {
                    if ($privilege->section == 'role') {
                        $roles[$privilege->id] = $privilege->toArray();
                    } else {
                        $permissions[$privilege->id] = $privilege->toArray();
                    }
                }
                foreach ($assignments as $assignment) {
                    if (array_key_exists($assignment->role_id, $roles)) {
                        if (array_key_exists($assignment->permission_id, $permissions)) {
                            $this->items[$assignment->role_id][] = $permissions[$assignment->permission_id]['name'];
                        }
                    }
                }
                foreach ($users as $user) {
                    if (array_key_exists($user->role_id, $roles)) {
                        $this->roles[$user->user_id][] = $roles[$user->role_id]['name'];
                        $this->users[$user->user_id][] = $roles[$user->role_id]['name'];
                        if (array_key_exists($user->role_id, $this->items)) {
                            $this->users[$user->user_id] = array_merge(
                                $this->users[$user->user_id],
                                $this->items[$user->role_id]
                            );
                        }
                    }
                }
                foreach ($myids as $myid) {
                    if (array_key_exists($myid->permission_id, $permissions)) {
                        $this->myids[$myid->user_id][$permissions[$myid->permission_id]['name']] = $myid->object_id;
                    }
                }
                $data = [
                    'users' => $this->users,
                    'roles' => $this->roles,
                    'items' => $this->items,
                    'myids' => $this->myids,
                ];
                $this->cache->put($this->name, $data, $this->config->get('privilege_cache_duration', 1));
            }
        }
    }

    /**
     * @param $privilege
     * @param $section
     * @param null         $object_id
     * @param null         $user_id
     */
    public function hasAccess($privilege, $section = null, $object_id = null, $user_id = null)
    {
        try {
            if (empty($user_id)) {
                $user_id = auth()->user()->id;
            }

            if (!empty($object_id) && $section == 'permission') {
                $permissions = Arr::get($this->myids, $user_id) ?: [];
                return in_array($object_id, Arr::get($permissions, $privilege));
            } elseif ($section == 'permission') {
                return in_array($privilege, Arr::get($this->items, $user_id));
            } elseif ($section == 'role') {
                return in_array($privilege, Arr::get($this->roles, $user_id));
            } elseif (empty($object_id)) {
                return in_array($privilege, Arr::get($this->users, $user_id));
            } else {
                return false;
            }
        } catch (Exception $e) {
            return app('ResponseError')->withException($e)->send();
        }
    }
}
