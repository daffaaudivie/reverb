<?php

namespace App\Services;

use App\Models\Complaint;
use App\Enums\Status;
use App\Events\StatusUpdated;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;


class ComplaintService
{

    public function getAll(): LengthAwarePaginator
    {
        return Complaint::with(['category', 'user'])
            ->latest()
            ->paginate(10);
    }

    public function find(Complaint $complaint): Complaint
    {
        return $complaint->load(['category', 'user']);
    }

    public function updateStatus(Complaint $complaint, Status $status): Complaint
    {
        Log::info('ComplaintService: Updating status', [
            'complaint_id' => $complaint->id,
            'old_status' => $complaint->status->value,
            'new_status' => $status->value
        ]);

        $complaint->update([
            'status' => $status,
        ]);

        $complaint->load(['category', 'user']);

        Log::info('ComplaintService: Broadcasting event', [
            'complaint_id' => $complaint->id,
            'status' => $complaint->status->value
        ]);

        event(new StatusUpdated($complaint));

        Log::info('ComplaintService: Event broadcasted');

        return $complaint;
    }
}