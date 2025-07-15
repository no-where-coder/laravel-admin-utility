<!DOCTYPE html>
<html lang="en" class="h-full bg-neutral-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Utility</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <style>
        html {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="h-full text-neutral-900 antialiased">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-neutral-200">
            <!-- Logo -->
            <div class="px-6 py-5 flex items-center gap-3 border-b border-neutral-200">
                <img src="{{ asset('vendor/admin-utility/assets/icon.png') }}"
                    alt="Admin Icon"
                    class="w-10 h-10 object-contain rounded-full shadow">
                <span class="text-lg font-semibold text-violet-600">Admin Utility</span>
            </div>

            <!-- Navigation -->
            <nav class="mt-4 space-y-1">
                @php
                    $nav = [
                        'Artisan' => route('admin.artisan.index'),
                        'CRUD' => route('admin.crud.index'),
                        'DB Backup' => route('admin.db.index'),
                        'ENV Editor' => route('admin.env.index'),
                        'System Info' => route('admin.system.index'),
                        'Maintenance' => route('admin.maintenance.index'),
                        'File Upload' => route('admin.upload.index'),
                    ];
                    $currentRoute = request()->url();
                @endphp

                @foreach ($nav as $label => $link)
                    @php $isActive = $currentRoute === $link; @endphp
                    <a href="{{ $link }}"
                       class="block px-6 py-2 text-sm rounded-md font-medium transition
                            {{ $isActive ? 'bg-violet-100 text-violet-700' : 'text-neutral-700 hover:bg-neutral-100' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 bg-neutral-50">
            <div class="max-w-7xl mx-auto py-6 px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
