<?php

namespace Sitic\Settings\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingItemResource extends JsonResource
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
            'name' => $this->name,
            'title' => $this->title,
            'type' => $this->type,
            'value' => $this->value,
            'setting' => new SettingResource($this->whenLoaded('setting'))
        ];
    }
}
