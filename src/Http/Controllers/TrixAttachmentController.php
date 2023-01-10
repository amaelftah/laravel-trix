<?php

namespace Te7aHoudini\LaravelTrix\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Te7aHoudini\LaravelTrix\Models\TrixAttachment;

class TrixAttachmentController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file',
            'modelClass' => 'required',
            'field' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $attachment = $request->file->store('/', $request->disk ?? config('laravel-trix.storage_disk'));

        $url = Storage::disk($request->disk ?? config('laravel-trix.storage_disk'))->url($attachment);

        TrixAttachment::create([
            'field' => $request->field,
            'attachable_type' => $request->modelClass,
            'attachment' => $attachment,
            'disk' => $request->disk ?? config('laravel-trix.storage_disk'),
        ]);

        return response()->json(['url' => $url], Response::HTTP_CREATED);
    }

    public function destroy($url)
    {
        $attachment = TrixAttachment::where('attachment', basename($url))->first();

        return response()->json(optional($attachment)->purge());
    }
}
