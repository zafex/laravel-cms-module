<?php

namespace Apiex\Common;

use Apiex\Entities;
use Closure;
use Illuminate\Contracts\Cache\Repository as CacheContract;
use Illuminate\Contracts\Config\Repository as ConfigContract;
use Illuminate\Support\Arr;

class Settings
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
     * @var mixed
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $options = [];

    /**
     * @param Cache $cache
     */
    public function __construct(CacheContract $cache, ConfigContract $config)
    {
        $this->cache = $cache;
        $this->config = $config;

        $this->name = $this->config->get('setting_cache_name') ?: 'settings';

        if (empty($this->options)) {
            if ($this->cache->has($this->name)) {
                $this->options = $this->cache->get($this->name);
            } else {
                $settings = Entities\Setting::all();
                foreach ($settings as $setting) {
                    $value = json_decode($setting->value);
                    $this->options[$setting->section] = json_last_error() == JSON_ERROR_NONE ? $value : $setting->value;
                }
                $this->cache->put($this->name, $this->options, $this->config->get('setting_cache_duration', 1));
            }
        }
    }

    /**
     * @param $section
     * @param Closure    $callback
     */
    public function getOption($section, Closure $callback = null)
    {
        if (Arr::has($this->options, $section)) {
            $data = Arr::get($this->options, $section);
        } else {
            $data = null;
        }
        if (is_callable($callback)) {
            $handler = $callback->bindTo($this, $this);
            return $handler($data);
        }
        return $data;
    }

    /**
     * @param $section
     * @param $data
     */
    public function setOption($section, $data)
    {
        $value = is_scalar($data) ? $data : json_encode($data);
        Entities\Setting::updateOrCreate(compact('section'), [
            'value' => $value,
        ]);
        $this->options[$section] = $data;
        $this->cache->put($this->name, $this->options, $this->config->get('setting_cache_duration', 1));
        return $this;
    }
}
