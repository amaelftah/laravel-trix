<?php

namespace Te7aHoudini\LaravelTrix\Pipes;

use Te7aHoudini\LaravelTrix\LaravelTrix;

class TrixEditor
{
    public function handle(LaravelTrix $trix, \Closure $next)
    {
        $trix->html .= "<trix-editor class='trix-content' input='{$trix->config['id']}' data-config='".json_encode($trix->config)."'></trix-editor>";

        return $next($trix);
    }
}
