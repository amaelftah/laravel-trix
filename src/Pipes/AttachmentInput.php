<?php

namespace Te7aHoudini\LaravelTrix\Pipes;

use Te7aHoudini\LaravelTrix\LaravelTrix;

class AttachmentInput
{
    public function handle(LaravelTrix $trix, \Closure $next)
    {
        $attachments = is_object($trix->model) ? $trix->model->trixAttachments()->pluck('attachment') : '[]';

        $trix->html .= "<input id='attachment-{$trix->config['id']}' value='{$attachments}' name='attachment-{$trix->loweredModelName}-trixFields[{$trix->config['field']}]' type='hidden'>";

        return $next($trix);
    }
}
