<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Complaints') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full px-6 lg:px-10">
            <div class="bg-white shadow rounded-lg overflow-hidden">

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-3 text-left">No</th>
                                <th class="px-6 py-3 text-left">Pelapor</th>
                                <th class="px-6 py-3 text-left">Judul</th>
                                <th class="px-6 py-3 text-left">Kategori</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Tanggal</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($complaints as $complaint)
                                <tr class="hover:bg-gray-50" data-complaint-id="{{ $complaint->id }}">
                                    <td class="px-6 py-4">
                                        {{ $loop->iteration + ($complaints->currentPage() - 1) * $complaints->perPage() }}
                                    </td>

                                    <td class="px-6 py-4">{{ $complaint->user->name }}</td>
                                    <td class="px-6 py-4">{{ $complaint->title }}</td>
                                    <td class="px-6 py-4">{{ $complaint->category->name }}</td>

                                    <td class="px-6 py-4">
                                        @php
                                            $statusColors = [
                                                'pending' => ['bg' => '#e5e7eb', 'text' => '#374151'],
                                                'diajukan' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                                                'diproses' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                                                'selesai' => ['bg' => '#dcfce7', 'text' => '#166534'],
                                                'ditolak' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                                            ];
                                            $current = $statusColors[$complaint->status->value] ?? $statusColors['pending'];
                                        @endphp
                                        
                                        <select 
                                            data-complaint-id="{{ $complaint->id }}"
                                            data-original="{{ $complaint->status->value }}"
                                            class="status-dropdown w-full max-w-[140px] px-3 py-2 rounded-md text-xs font-semibold border-0 focus:ring-2 focus:ring-indigo-500 cursor-pointer transition-colors"
                                            style="background-color: {{ $current['bg'] }}; color: {{ $current['text'] }};">
                                            @foreach(\App\Enums\Status::cases() as $status)
                                                @php
                                                    $color = $statusColors[$status->value] ?? $statusColors['pending'];
                                                @endphp
                                                <option 
                                                    value="{{ $status->value }}" 
                                                    {{ $complaint->status->value === $status->value ? 'selected' : '' }}
                                                    data-bg="{{ $color['bg'] }}"
                                                    data-text="{{ $color['text'] }}">
                                                    {{ $status->label() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ optional($complaint->created_at)->format('d M Y') ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-center space-x-2">
                                        <a href="{{ route('admin.complaints.show', $complaint->id) }}"
                                           class="inline-block bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 rounded transition">
                                            Detail
                                        </a>
                                        <a href="{{ route('admin.complaints.show', $complaint->id) }}"
                                           class="inline-block bg-transparent hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 rounded transition">
                                            Balas
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-6 text-center text-gray-500">
                                        Belum ada data pengaduan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4">
                    {{ $complaints->links() }}
                </div>

            </div>
        </div>
    </div>

    <!-- Notification Container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50"></div>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    
    <script>
        // Initialize Echo
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: '{{ config('broadcasting.connections.reverb.key') }}',
            wsHost: '{{ config('broadcasting.connections.reverb.options.host') }}',
            wsPort: {{ config('broadcasting.connections.reverb.options.port', 8080) }},
            wssPort: {{ config('broadcasting.connections.reverb.options.port', 8080) }},
            forceTLS: {{ config('broadcasting.connections.reverb.options.scheme') === 'https' ? 'true' : 'false' }},
            enabledTransports: ['ws', 'wss'],
            disableStats: true,
        });

        // Listen for status updates
        let isUpdating = false; // Prevent double notifications
        
        window.Echo.channel('complaints')
            .listen('.complaint.status.updated', (e) => {
                if (!isUpdating && e.complaint?.data) {
                    updateComplaintRow(e.complaint.data);
                    showNotification('Status complaint diperbarui oleh user lain', 'info');
                }
            });

        // Handle status dropdown changes
        document.querySelectorAll('.status-dropdown').forEach(select => {
            select.addEventListener('change', async function(e) {
                const complaintId = this.dataset.complaintId;
                const newStatus = this.value;
                const originalValue = this.dataset.original;

                if (newStatus === originalValue) return;

                this.disabled = true;
                isUpdating = true;

                try {
                    const response = await fetch(`/admin/complaints/${complaintId}/update-status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: newStatus })
                    });

                    if (!response.ok) {
                        const data = await response.json();
                        throw new Error(data.message || 'Gagal memperbarui status');
                    }

                    const data = await response.json();
                    this.dataset.original = newStatus;
                    updateStatusColors(this, newStatus);
                    showNotification('Status berhasil diperbarui', 'success');
                    
                } catch (error) {
                    this.value = originalValue;
                    updateStatusColors(this, originalValue);
                    showNotification(error.message, 'error');
                } finally {
                    this.disabled = false;
                    setTimeout(() => { isUpdating = false; }, 500);
                }
            });
        });

        function updateComplaintRow(complaint) {
            const row = document.querySelector(`tr[data-complaint-id="${complaint.id}"]`);
            if (!row) return;

            const statusSelect = row.querySelector('.status-dropdown');
            if (statusSelect && statusSelect.value !== complaint.status) {
                statusSelect.value = complaint.status;
                statusSelect.dataset.original = complaint.status;
                updateStatusColors(statusSelect, complaint.status);
            }
        }

        function updateStatusColors(select, status) {
            const option = select.querySelector(`option[value="${status}"]`);
            if (option) {
                select.style.backgroundColor = option.dataset.bg;
                select.style.color = option.dataset.text;
            }
        }

        let notificationTimeout;
        function showNotification(message, type = 'success') {
            const container = document.getElementById('notification-container');
            container.innerHTML = '';
            clearTimeout(notificationTimeout);

            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                info: 'bg-blue-500'
            };

            const notification = document.createElement('div');
            notification.className = `${colors[type]} px-6 py-3 rounded-lg shadow-lg text-white transition-opacity duration-300`;
            notification.textContent = message;
            container.appendChild(notification);

            notificationTimeout = setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>
</x-app-layout>