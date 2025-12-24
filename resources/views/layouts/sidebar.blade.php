<div class="w-56 bg-white shadow-md min-h-screen shrink-0">
    <nav class="mt-5 px-2">

        @if(auth()->user()->isAdmin())

            <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Admin Menu
            </div>

            <a href="{{ route('admin.dashboard') }}"
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-md
               {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <span class="mr-3 text-xl">🏠</span>
                Dashboard
            </a>

            <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-50 hover:text-gray-900">
                <span class="mr-3 text-xl">👥</span>
                Users
            </a>

            <a href="{{ route('admin.complaints.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-50 hover:text-gray-900">
                <span class="mr-3 text-xl">📨</span>
                Complaints
            </a>

        @else

            <div class="px-3 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                User Menu
            </div>

            <a href="{{ route('dashboard') }}"
               class="group flex items-center px-3 py-2 text-sm font-medium rounded-md
               {{ request()->routeIs('dashboard') ? 'bg-gray-200 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <span class="mr-3 text-xl">🏠</span>
                Dashboard
            </a>

            <a href="{{ route('admin.complaints.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium text-gray-600 rounded-md hover:bg-gray-50 hover:text-gray-900">
                <span class="mr-3 text-xl">📨</span>
                Complaints
            </a>

        @endif

    </nav>
</div>