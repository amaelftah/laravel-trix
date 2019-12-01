<?php

namespace Te7aHoudini\LaravelTrix;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Te7aHoudini\LaravelTrix\Pipes\AttachmentInput;
use Te7aHoudini\LaravelTrix\Pipes\Input;
use Te7aHoudini\LaravelTrix\Pipes\Styles;
use Te7aHoudini\LaravelTrix\Pipes\TrixEditor;

class LaravelTrix
{
    public $model;

    public $loweredModelName;

    public $config;

    public $html = '';

    public function make($model, $field, $config = [])
    {
        $this->model = $model;

        $this->loweredModelName = Str::lower(class_basename($model));

        $this->formatFieldConfig($field, $config);

        return $this;
    }

    public function formatFieldConfig($field, $config)
    {
        $config['id'] = $config['id'] ?? $this->loweredModelName.'-'.$field.'-'.(is_object($this->model) && $this->model->exists ? $this->model->id : 'new-model');

        $config['modelClass'] = is_object($this->model) ? get_class($this->model) : $this->model;

        $config['field'] = $field;

        $this->config = $config;
    }

    public function __toString()
    {
        return app(Pipeline::class)
            ->send($this)
            ->through([
                Styles::class,
                Input::class,
                AttachmentInput::class,
                TrixEditor::class,
            ])
            ->then($this->renderContainer());
    }

    public function renderContainer()
    {
        return function () {
            $tag = $this->config['containerElement'] ?? 'span';

            return "<$tag v-pre id='container-{$this->config['id']}'> $this->html </$tag>";
        };
    }
}
