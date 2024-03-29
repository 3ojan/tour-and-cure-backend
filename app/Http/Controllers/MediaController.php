<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeesUsersClinics;
use App\Http\Requests\MediaFiles;
use App\Models\Media;
use App\Models\Model;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Upload file
     *
     * @param $file
     * @return string
     */
    private function uploadFile($file): string
    {
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public', $fileName);

        return 'storage' . DIRECTORY_SEPARATOR . $fileName;
    }

    /**
     * Store file information to Media_types table and associate with employee.
     *
     * @param MediaFiles\MediaFileStoreRequest $request
     * @return JsonResponse
     */
    public function store(MediaFiles\MediaFileStoreRequest $request): JsonResponse
    {
        $fileAttribute = key($request->file());
        $file = $request->file($fileAttribute);

        $path = app(MediaController::class)->uploadFile($file);

        $media = Media::create([
            'mimetype' => $file->getMimeType(),
            'name' => $file->getClientOriginalName(),
            'ext' => $file->getClientOriginalExtension(),
            'path' => $path,
            'size' => $file->getSize(),
            'attribute_name' => $fileAttribute,
        ]);

        return $this->success($media->id,'File stored successfully!');
    }

    /**
     * Update file information on Media_types table associated with specific employee.
     *
     * @param Request $request
     * @param Model $model
     * @return JsonResponse
     */
    public function update(Request $request, Model $model)
    {
        $fileAttribute = key($request->file());
        $file = $request->file($fileAttribute);
        $media = $model->media;

        if ($media) {
            app(MediaController::class)->destroyPath($media->path);
        }

        $path = app(MediaController::class)->uploadFile($file);

        $model->media()->update([
            'mimetype' => $file->getMimeType(),
            'name' => $file->getClientOriginalName(),
            'ext' => $file->getClientOriginalExtension(),
            'path' => $path,
            'size' => $file->getSize(),
            'attribute_name' => $fileAttribute,
        ]);

        return $this->success('','File updated successfully!');
    }

    private function destroyFile($path)
    {
        $file = str_replace('storage/', '', $path);

        Storage::disk('public')->delete($file);

        return response()->json(['message' => 'Media file deleted successfully']);
    }

    public function destroy(Request $request, Model $model)
    {
        $media = $model->media;

        if ($media) {
            app(MediaController::class)->destroyFile($media->path);

            $media->delete();

            return response()->json(['message' => 'Media record deleted successfully']);
        }

        return response()->json(['message' => 'Media record not found'], 404);
    }
}
