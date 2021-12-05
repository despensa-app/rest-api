<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $toArray = parent::toArray($request);
        $imgUrl = $toArray['img_url'];
        
        if (Storage::exists($imgUrl)) {
            $toArray['img_url'] = URL::asset(Storage::url($imgUrl));
        }
        
        return $toArray;
    }
}
