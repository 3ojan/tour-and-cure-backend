<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeesUsersClinics;
use App\Models\Model;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\LogoImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    use HttpResponses;

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
     * @param EmployeesUsersClinics\EmployeeUserClinicStoreRequest $request
     * @param Model $model
     * @return JsonResponse
     */
    public function store(EmployeesUsersClinics\EmployeeUserClinicStoreRequest $request, Model $model): JsonResponse
    {
        $fileAttribute = key($request->file());
        $file = $request->file($fileAttribute);

        $path = app(MediaController::class)->uploadFile($file);

        $model->media()->create([
            'mimetype' => $file->getMimeType(),
            'name' => $file->getClientOriginalName(),
            'ext' => $file->getClientOriginalExtension(),
            'path' => $path,
            'size' => $file->getSize(),
            'attribute_name' => $fileAttribute,
        ]);

        return $this->success('','File stored successfully!');
    }

    /**
     * Update file information on Media_types table associated with specific employee.
     *
     * @param EmployeesUsersClinics\EmployeeUserClinicStoreRequest $request
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

    public function uploadLogo(Request $request): string
    {
        \Log::info('request: ' . $request);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            \Log::info('File Name: ' . $file->getClientOriginalName());
            \Log::info('File Extension: ' . $file->getClientOriginalExtension());
            \Log::info('File Real Path: ' . $file->getRealPath());
            \Log::info('File Size: ' . $file->getSize());
            \Log::info('File Mime Type: ' . $file->getMimeType());
        }
        if ($request->file('file')->isValid()) {
            \Log::info("File upload was successful!");
        } else {
            \Log::info("File upload encountered an error: " . $request->file('file')->getErrorMessage());
        }
        $path = $request->file('file')->store('files/logos');
        // Save the path to the LogoImage model
        $info = pathinfo($path);
        $directory = $info['dirname'];
        $fileName = $info['basename'];

        $logo = LogoImage::create([
            'file_name' => $fileName,
            'path' => $directory,
            'user_id' => Auth::id(),
        ]);

        // Return a response to the client
        return response()->json([
            'message' => 'Logo uploaded and saved successfully!',
            'response' => $logo,
        ], 201);
    }
}
