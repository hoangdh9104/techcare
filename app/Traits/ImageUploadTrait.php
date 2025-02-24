<?php

namespace App\Traits;

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
};
