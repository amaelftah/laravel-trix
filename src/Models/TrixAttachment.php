<?php

namespace Te7aHoudini\LaravelTrix\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TrixAttachment extends Model
{
    protected $guarded = [];

    public function purge()
    {
        Storage::disk($this->disk)->delete($this->attachment);

        $this->delete();
    }
}
