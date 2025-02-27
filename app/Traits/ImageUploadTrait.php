<?php

namespace App\Traits;

use File;
use Illuminate\Http\Request;


trait ImageUploadTrait
{
    public function uploadImage(Request $request, $inputName, $path)
    {
        // kiểm tra nếu request có ảnh
        if ($request->hasFile($inputName)) {
            $img = $request->{$inputName};
            $ext = $img->getClientOriginalExtension();
            $imgName = 'media_' . uniqid() . '.' . $ext;
            $img->move(public_path($path), $imgName);
            return $path . '/' . $imgName;
        }
    }
    public function uploadMultiImage(Request $request, $inputName, $path)
    {
        // kiểm tra nếu request có ảnh
        $imgPaths = [];
        if ($request->hasFile($inputName)) {
            $imgs = $request->{$inputName};
            foreach ($imgs as $img) {
                $ext = $img->getClientOriginalExtension();
                $imgName = 'media_' . uniqid() . '.' . $ext;
                $img->move(public_path($path), $imgName);
                $imgPaths[] =  $path . '/' . $imgName;
            }
        }
        return $imgPaths;
    }
    public function updateImage(Request $request, $inputName, $path, $oldPath = null)
    {
        // kiểm tra nếu request có ảnh
        if ($request->hasFile($inputName)) {

            if (File::exists(public_path($oldPath))) {
                File::delete(public_path($oldPath));
            }

            $img = $request->{$inputName};
            $ext = $img->getClientOriginalExtension();
            $imgName = 'media_' . uniqid() . '.' . $ext;
            $img->move(public_path($path), $imgName);
            return $path . '/' . $imgName;
        }
    }
    /** handle delte file*/
    public function deleteImage(string $path)
    {
        if (File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
};
