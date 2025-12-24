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
                    <table class="w-full divide-y divide-gray-200 text-sm ">
                        <thead class="bg-gray-50">
                            <tr class="text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">No</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Pelapor</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Judul</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Kategori</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-center font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($complaints as $complaint)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-center">
                                        {{ $loop->iteration + ($complaints->currentPage() - 1) * $complaints->perPage() }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $complaint->user->name }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $complaint->title }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $complaint->category->name }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @php
                                            $statusColor = match($complaint->status) {
                                                'diajukan' => 'bg-yellow-100 text-yellow-800',
                                                'diproses' => 'bg-blue-100 text-blue-800',
                                                'selesai'  => 'bg-green-100 text-green-800',
                                                'ditolak'  => 'bg-red-100 text-red-800',
                                                default    => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp

                                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $statusColor }}">
                                            {{ ucfirst($complaint->status) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ optional($complaint->created_at)->format('d M Y') ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4 text-center space-x-2">
                                        <a href="{{ route('admin.complaints.show', $complaint->id) }}"
                                           class="text-indigo-600 hover:underline">
                                            Detail
                                        </a>
                                        <button
                                        href="{{ route('admin.complaints.show', $complaint->id) }}"
                                        class="bg-transparent hover:bg-green-200 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 rounded">
                                            Balas
                                        </button>
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

                <!-- Pagination -->
                <div class="px-6 py-4">
                    {{ $complaints->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>