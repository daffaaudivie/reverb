<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComplaintResource;
use App\Services\ComplaintService;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use App\Enums\Status;

class ComplaintController extends Controller
{
    public function __construct(
        protected ComplaintService $complaintService
    ) {}
    
    public function index()
    {
        $complaints = $this->complaintService->getAll();

        return ComplaintResource::collection($complaints);
    }

    public function show(Complaint $complaint)
    {
        return new ComplaintResource(
            $this->complaintService->find($complaint)
        );
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => ['required', new Enum(Status::class)],
        ]);

        $status = Status::from($request->status);

        return new ComplaintResource(
            $this->complaintService->updateStatus($complaint, $status)
        );
    }
}
