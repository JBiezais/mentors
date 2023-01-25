<?php


namespace App\Actions;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class UploadFileAction{

    const IMAGE_PATH = 'image';
    const CROPPED_IMAGE_PATH = 'image/cropped';

    public function upload(UploadedFile $file): bool|string
    {
        $img = Image::make($file->path());

        $fileName = time().$file->getClientOriginalName();
        $file->storeAs(self::IMAGE_PATH, $fileName);

        $filePath = self::CROPPED_IMAGE_PATH.'/'.$fileName;

        $img->resize(400, 400, function ($const) {
            $const->aspectRatio();
        })->crop(400, 400)->save($filePath);

        return $filePath;

    }
}
