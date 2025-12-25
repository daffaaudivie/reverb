<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Models\User;
use App\Enums\Status;
use App\Events\StatusUpdated;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Log;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with(['category', 'user'])->paginate(10);
        if(auth()->user()->isAdmin())
            return view('admin.complaint.complaint_index', compact('complaints'));
        else
            return view('user.complaint.complaint_index', compact('complaints'));
    }

    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        Log::info('updateStatus called', [
            'complaint_id' => $complaint->id,
            'request_data' => $request->all(),
            'current_status' => $complaint->status->value ?? 'null'
        ]);

        try {
            $validated = $request->validate([
                'status' => ['required', new Enum(Status::class)]
            ]);

            Log::info('Validation passed', [
                'validated_status' => $validated['status']
            ]);

            $status = Status::from($validated['status']);
            
            Log::info('Status enum created', [
                'status_value' => $status->value,
                'status_name' => $status->name
            ]);

            $complaint->update([
                'status' => $status,
            ]);

            $complaint->refresh();
   
            $complaint->load(['category', 'user']);

            Log::info('Complaint updated', [
                'complaint_id' => $complaint->id,
                'new_status' => $complaint->status->value
            ]);
            event(new StatusUpdated($complaint));

            Log::info('Event broadcasted successfully');

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'complaint' => [
                    'id' => $complaint->id,
                    'status' => $complaint->status->value,
                    'title' => $complaint->title,
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\ValueError $e) {
            Log::error('Invalid status value', [
                'error' => $e->getMessage(),
                'status_received' => $request->status
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid: ' . $request->status,
                'error' => $e->getMessage()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Failed to update status', [
                'complaint_id' => $complaint->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }
}