<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'reference' => $this->reference,
            'startDate' => $this->start_date,
            'noOfContacts' => $this->contacts_count ?? 0,
            'contacts' => ContactResource::collection($this->whenLoaded('contacts')),
        ];
    }
}
