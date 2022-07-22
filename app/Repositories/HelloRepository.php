<?php

namespace App\Repositories;

use App\Models\Hello;

class HelloRepository
{
    public function store($hello)
    {
        return Hello::create($hello);
    }
    
    public function update($helloInput)
    {
        $hello = Hello::find($helloInput["id"]);
        $oldImage = $hello->getImageName();
        $hello->title_ar = $helloInput["title_ar"];
        $hello->title_en = $helloInput["title_en"];
        $hello->list = $helloInput["list"];
        $hello->image = isset($helloInput["image"]) ? $helloInput["image"] : $oldImage;
        $hello->save();
        return ["old_image" => $oldImage, "updated_hello" => $hello];
    }

    public function delete($id)
    {
        $hello = Hello::find($id);
        $oldImage = null;
        if ($hello) {
            $oldImage = $hello->getImageName();
            $hello->delete();
        }
        return $oldImage;
    }
    public function getPage($pageSize, $text)
    {
        return Hello::whereRaw('LOWER(`title_ar`) LIKE ? or LOWER(`title_en`) LIKE ?', [
            "%" . strtolower($text) . '%',
            "%" . strtolower($text) . '%',
        ])->paginate($pageSize);
    }
    
}
