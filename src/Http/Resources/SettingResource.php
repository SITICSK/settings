<?php

namespace Sitic\Settings\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'lang' => $this->lang,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'created_at' => strtotime($this->created_at),
            'updated_at' => strtotime($this->updated_at),
            'deleted_at'=> strtotime($this->deleted_at),
            'settingItems' => SettingItemResource::collection($this->whenLoaded('settingItems'))
        ];
    }
}
