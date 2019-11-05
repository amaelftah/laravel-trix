<?php

namespace Te7aHoudini\LaravelTrix\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Te7aHoudini\LaravelTrix\Models\TrixAttachment;
use Te7aHoudini\LaravelTrix\Models\TrixRichText;

trait HasTrixRichText
{
    protected $savedTrixFields = [];

    protected $savedAttachments = [];

    public static function bootHasTrixRichText()
    {
        static::saving(function ($model) {
            $trixInputName = Str::lower(class_basename($model)).'-trixFields';

            $model->savedTrixFields = Arr::get($model, $trixInputName, []);

            $model->savedAttachments = Arr::get($model, 'attachment-'.$trixInputName, []);

            unset($model->$trixInputName);
            unset($model->{'attachment-'.$trixInputName});
        });

        static::saved(function ($model) {
            foreach ($model->savedTrixFields as $field => $content) {
                TrixRichText::updateOrCreate([
                    'model_id' => $model->id,
                    'model_type' => $model->getMorphClass(),
                    'field' => $field,
                ], [
                    'field' => $field,
                    'content' => $content,
                ]);

                $attachments = Arr::get($model->savedAttachments, $field, []);

                TrixAttachment::whereIn('attachment', is_string($attachments) ? json_decode($attachments) : $attachments)
                    ->update([
                        'is_pending' => 0,
                        'attachable_id' => $model->id,
                    ]);
            }

            $model->savedTrixFields = [];
        });
    }

    public function trix($field, $config = [])
    {
        return app('laravel-trix')->make($this, $field, $config);
    }

    public function trixRichText()
    {
        return $this->morphMany(TrixRichText::class, 'model');
    }

    public function trixAttachments()
    {
        return $this->morphMany(TrixAttachment::class, 'attachable');
    }
}
