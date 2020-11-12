<?php

namespace Te7aHoudini\LaravelTrix\Pipes;

use Te7aHoudini\LaravelTrix\LaravelTrix;

class Input
{
    public function handle(LaravelTrix $trix, \Closure $next)
    {
        $html = is_object($trix->model) && $trix->model->exists ? htmlspecialchars(optional($trix->model->trixRichText()->where('field', $trix->config['field'])->first())->content, ENT_QUOTES) : old($trix->loweredModelName.'-trixFields.'.$trix->config['field'], '');

        $trix->html .= "<input id='{$trix->config['id']}' value='{$html}' name='{$trix->loweredModelName}-trixFields[{$trix->config['field']}]' type='hidden'>";

        return $next($trix);
    }
}
