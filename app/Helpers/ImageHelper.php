<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use App\Image;

class ImageHelper
{

    public static function upload($image, $name, $path)
    {
        $image->move($path, $name);
    }

    public static function save($model, $path)
    {
        $image = new Image;
        $image->image_path = $path;
        $image = $model->image()->save($image);
        return $image;
    }

    public static function delete($model)
    {
        if (!is_null($model->image()->first())) {
            unlink($model->image()->first()->image_path); //delete current image from public folder
            $model->image()->delete(); //delete current image from DB
        }
    }

}
