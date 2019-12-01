<?php

namespace Te7aHoudini\LaravelTrix\Pipes;

use Illuminate\Support\Arr;
use Te7aHoudini\LaravelTrix\LaravelTrix;

class Styles
{
    public function handle(LaravelTrix $trix, \Closure $next)
    {
        $this->hideToolBar($trix);
        $this->hideTools($trix);
        $this->hideButtonIcons($trix);

        $trix->html = "<laravel-trix-instance-style style='display:none;'> {$trix->html} </laravel-trix-instance-style>";

        return $next($trix);
    }

    public function hideToolBar($trix)
    {
        if (Arr::get($trix->config, 'hideToolbar')) {
            $trix->html .= "#container-{$trix->config['id']} trix-toolbar{display:none;}";
        }
    }

    public function hideTools($trix)
    {
        $trix->html .= collect(Arr::get($trix->config, 'hideTools', []))
            ->map(function ($tool) use ($trix) {
                return "#container-{$trix->config['id']} .trix-button-group--$tool";
            })
            ->implode(',').(Arr::get($trix->config, 'hideTools', []) ? '{display:none;}' : '');
    }

    public function hideButtonIcons($trix)
    {
        $trix->html .= collect(Arr::get($trix->config, 'hideButtonIcons', []))
            ->map(function ($iconClass) use ($trix) {
                return "#container-{$trix->config['id']} .trix-button--icon-$iconClass";
            })
            ->implode(',').(Arr::get($trix->config, 'hideButtonIcons', []) ? '{display:none;}' : '');
    }
}
