<?php

namespace App\Events;

use App\Models\Company;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CompanyCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Company $company) {}

    public function broadcastOn(): array
    {
        return [new Channel('companies')];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->company->id,
            'name' => $this->company->name,
            'industry' => $this->company->industry,
            'created_at' => $this->company->created_at?->toIso8601String(),
        ];
    }
}
