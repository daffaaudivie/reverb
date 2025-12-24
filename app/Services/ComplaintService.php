<?php

namespace App\Services;

use App\Models\Complaint;
use App\Enums\Status;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
        $complaint->update([
            'status' => $status,
        ]);

        return $complaint->load(['category', 'user']);
    }
}
