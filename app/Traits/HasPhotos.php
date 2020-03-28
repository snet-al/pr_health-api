<?php

namespace App\Traits;

use App\Helpers\ImageHelper;
use App\Photo;
use ReflectionClass;

trait HasPhotos
{
    /**
     * An activity can have one or more photos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function photos()
    {
        return $this->morphMany(Photo::class, 'imageable');
    }

    /**
     * Add photo.
     *
     * @param $photo
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addPhoto($photo)
    {
        return $this->photos()->save($photo);
    }

    /**
     * @param Request $request
     * @param array   $options
     *
     * @return \App\Photo
     */
    public function createPhoto($request, $options = [])
    {
        $fileParam = $options['file_param'] ?? 'photo';

        if ($request->has($fileParam) || $request->hasFile($fileParam)) {
            if ($request->hasFile($fileParam)) {
                $photo = Photo::createFromForm($request->file($fileParam), self::PHOTO_DIR, $this->id);
            } else {
                $photo = new Photo();

                $className = strtolower((new ReflectionClass($this))->getShortName());
                $filenamePrefix = $className . '_' . $fileParam;
                $file = ImageHelper::saveFromBase64($request->get($fileParam), self::PHOTO_DIR, $this->id, $filenamePrefix);
                $photo->saveAs($file, self::PHOTO_DIR, $this->id, true);
            }
            $this->addPhoto($photo);
        }

        return $photo;
    }

    public function deletePhotos()
    {
        foreach ($this->photos as $photo) {
            $photo->deletePhoto();
        }

        return $this;
    }
}
