<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            // Tránh lỗi nếu user chưa load hoặc bị null
            'user' => $this->whenLoaded('user', fn () => $this->user),
            'thumbnail' => $this->thumbnail,
            'description' => $this->description,
            'publish_date' => format_date($this->publish_date),
            'status' => $this->status->toArray(),
            'created_at' => format_datetime($this->created_at),
        ];
    }

}
