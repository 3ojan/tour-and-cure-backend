<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogoImage;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
    /**
     * Upload file
     */
    public function uploadFile(Request $request): string
    {
        $path = $request->file('file')->store('files');
        
        return $path;
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


        dd($request->allFiles());
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
            'response' => $path,
        ], 201);
    }
}

/*
class ImageUploadController extends Controller
{
    public function updateProfilePicture(Request $request) {
        $image = $request->file('profileImage');

        // Stores the Image into a folder called Avatars, 
        // image name is generated and assigned to $path
        // disk("public") is the local directory storage/app/public

        $details = new UserDetail;
        $path = Storage::disk('public')->put('avatars', $image);
        $details->avatar = $path;

        $user = User::find(auth()->user()->id);

        $user->details()->updateOrCreate(['user_id' => auth()->user()->id],
        ['avatar'=> $path]);

        error_log('File Name: '.$image->getClientOriginalName());
        error_log('File Extension: '.$image->getClientOriginalExtension());
        error_log('File Real Path: '.$image->getRealPath());
        error_log('File Size: '.$image->getSize());
        error_log('File Mime Type: '.$image->getMimeType());
        error_log($path);

        return response()->json($path);


     }

     public function getProfilePicture(Request $request) {
        $user = User::find(auth()->user()->id);
        $avatar = User::find(1)->avatar;

     }
}
*/