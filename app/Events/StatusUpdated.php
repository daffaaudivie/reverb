<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Complaint;
use Illuminate\Support\Facades\Log;

class StatusUpdated implements ShouldBroadcastNow  
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public function __construct(
        public Complaint $complaint
    ) {
        Log::info('StatusUpdated event created', [
            'complaint_id' => $complaint->id,
            'status' => $complaint->status->value
        ]);
    }

    public function broadcastWith(): array
    {
        $data = [
            'complaint' => [
                'data' => [
                    'id' => $this->complaint->id,
                    'title' => $this->complaint->title,
                    'status' => $this->complaint->status->value,
                    'category' => [
                        'name' => $this->complaint->category->name ?? null
                    ],
                    'user' => [
                        'name' => $this->complaint->user->name ?? null
                    ],
                ]
            ]
        ];

        Log::info('Broadcasting data', $data);

        return $data;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('complaints');
    }

    public function broadcastAs(): string
    {
        return 'complaint.status.updated';
    }
}