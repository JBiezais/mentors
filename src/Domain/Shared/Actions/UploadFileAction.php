<?php

namespace src\Domain\Shared\Actions;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UploadFileAction
{
    const IMAGE_PATH = 'image';
    const CROPPED_IMAGE_PATH = 'image/cropped';

    public function upload(UploadedFile $file, bool $resize = true): bool|string
    {
        $originalName = $file->getClientOriginalName();
        $timestamp = time();

        $nameWithoutExtension = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        $fileName = Str::snake($timestamp . '_' . $nameWithoutExtension) . '.' . $extension;
        $file->storeAs(self::IMAGE_PATH, $fileName);

        $filePath = self::IMAGE_PATH . '/' . $fileName;

        if ($resize) {
            $filePath = self::CROPPED_IMAGE_PATH . '/' . $fileName;

            $manager = new ImageManager(new Driver());
            $img = $manager->read($file->path());
            $img->cover(400, 400)->save(storage_path('app/' . $filePath));
        }

        return $filePath;
    }
}
