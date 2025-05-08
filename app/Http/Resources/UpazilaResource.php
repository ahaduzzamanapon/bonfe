<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UpazilaResource extends JsonResource
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
            'dis_id' => $this->dis_id,
            'name_en' => $this->name_en,
            'name_bn' => $this->name_bn,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
