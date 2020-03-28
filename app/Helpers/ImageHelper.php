<?php

namespace App\Helpers;

use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImageHelper.
 */
class ImageHelper
{
    const SMALL = 'small';
    const MEDIUM = 'medium';
    const LARGE = 'large';

    /**
     * Crops and saves a given image.
     *
     * @param UploadedFile $file
     * @param string       $resourceType
     * @param int          $resourceId
     *
     * @return mixed
     */
    public static function crop(UploadedFile $file, $resourceType, $resourceId, $filenamePrefix = null)
    {
        $filename = StringHelper::sanitizeFilename($file->getClientOriginalName());
        $newDir = self::uploadDirectoryName($resourceType, $resourceId);
        $filenameWithExtension = $filename['filenameWithExtension'];
        if ($filenamePrefix) {
            $filenameWithExtension = $filenamePrefix . '_' . $filenameWithExtension;
        }

        $file->move($newDir, $filenameWithExtension);
        $originalFilePath = $newDir . DIRECTORY_SEPARATOR . $filenameWithExtension;

        if (config()->get('image.' . $resourceType)) {
            /**
             * @var string
             * @var array  $sizes
             */
            foreach (config()->get('image.' . $resourceType) as $type => $sizes) {
                $newFileName = $type . '_' . $filenameWithExtension;

                Image::make($originalFilePath)
                    ->fit((int) $sizes['w'], (int) $sizes['h'])
                    ->save($newDir . DIRECTORY_SEPARATOR . $newFileName);
            }
        } else {
            Image::make($originalFilePath)->save($newDir . DIRECTORY_SEPARATOR . $filenameWithExtension);
        }

        return $filenameWithExtension;
    }

    /**
     * @param string $file
     * @param $resourceType
     * @param $resourceId
     * @param null $filenamePrefix
     *
     * @return string
     */
    public static function saveFromBase64($file, $resourceType, $resourceId, $filenamePrefix = null)
    {
        $newDir = self::uploadDirectoryName($resourceType, $resourceId);
        $imgdata = base64_decode($file);
        $f = finfo_open();
        $mimeType = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
        $fileName = Carbon::now()->timestamp . '_' . $filenamePrefix . '.' . explode('/', substr($mimeType, 5))[1];
        Image::make($file)->save($newDir . DIRECTORY_SEPARATOR . $fileName);
        $thumbnailFileName = 'tn-' . $fileName;
        Image::make($file)->fit(200)->save($newDir . DIRECTORY_SEPARATOR . $thumbnailFileName);

        return $fileName;
    }

    /**
     * @param string   $resourceType
     * @param int|null $resourceId
     *
     * @return string
     */
    public static function uploadDirectoryName($resourceType, $resourceId = null)
    {
        $directory = base_path() . config()->get('image.upload_path') . DIRECTORY_SEPARATOR . $resourceType;

        if ($resourceId) {
            $directory .= DIRECTORY_SEPARATOR . $resourceId;
        }

        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        return $directory;
    }

    /**
     * @param string $resourceType
     * @param int    $resourceId
     * @param string $filename
     *
     * @return string
     */
    public static function path($resourceType, $resourceId, $filename)
    {
        return config()->get('image.image_path') .
            DIRECTORY_SEPARATOR . $resourceType .
            DIRECTORY_SEPARATOR . $resourceId .
            DIRECTORY_SEPARATOR . $filename;
    }
}
