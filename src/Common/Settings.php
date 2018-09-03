<?php

namespace Apiex\Common;

use Apiex\Entities;
use Closure;
use Illuminate\Cache\Repository as Cache;

class Settings
{
    /**
     * @var mixed
     */
    protected $cache;

    /**
     * @var mixed
     */
    protected $options = [];

    /**
     * @param Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
        if (empty($this->options)) {
            if ($this->cache->has('settings')) {
                $this->options = $this->cache->get('settings');
            } else {
                $settings = Entities\Setting::all();
                foreach ($settings as $setting) {
                    $value = json_decode($setting->value);
                    $this->options[$setting->section] = json_last_error() == JSON_ERROR_NONE ? $value : $setting->value;
                }
                $this->cache->put('settings', $this->options, config('setting_cache_duration', 1));
            }
        }
    }

    /**
     * @param $section
     * @param Closure    $callback
     */
    public function getOption($section, Closure $callback = null)
    {
        if (array_has($this->options, $section)) {
            $data = array_get($this->options, $section);
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
        $this->cache->put('settings', $this->options, config('setting_cache_duration', 1));
        return $this;
    }
}
