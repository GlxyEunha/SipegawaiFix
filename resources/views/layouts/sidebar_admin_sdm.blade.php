<div x-cloak :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>
    
<div x-cloak :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-gray-900 lg:translate-x-0 lg:static lg:inset-0">
    <div class="flex items-center justify-center mt-8">
        <div class="flex items-center">
            <img src="/beacukai.png" alt="SIPEGAWAI Logo" class="w-12 h-12">
            <span class="mx-2 text-2xl font-semibold text-white">SIPEGAWAI</span>
        </div>
    </div>
    
    <nav class="mt-10">
        <a class="flex items-center px-6 py-2 mt-4 text-gray-100 bg-gray-700 bg-opacity-25" href="{{ route('admin_sdm.dashboard') }}">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
            </svg>
            <span class="mx-3">Dashboard</span>
        </a>

        <div x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100">
                <div class="flex items-center">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z" />
                    </svg>
                    <span class="mx-3">Rolling Pegawai</span>
                </div>
                <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" class="mt-2 space-y-2 bg-gray-800">
                <a href="{{ route('rolling.index') }}" class="block px-8 py-2 text-gray-400 hover:bg-gray-700 hover:text-gray-100">Rolling</a>
                <a href="{{ route('rolling.hasil') }}" class="block px-8 py-2 text-gray-400 hover:bg-gray-700 hover:text-gray-100">Hasil</a>
            </div>
        </div>

        <div x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center justify-between w-full px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100">
                <div class="flex items-center">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z" />
                    </svg>
                    <span class="mx-3">Riwayat</span>
                </div>
                <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" class="mt-2 space-y-2 bg-gray-800">
                <a href="{{ route('admin_sdm.daftar_tugas') }}" class="block px-8 py-2 text-gray-400 hover:bg-gray-700 hover:text-gray-100">Riwayat Tugas</a>
                <a href="#" class="block px-8 py-2 text-gray-400 hover:bg-gray-700 hover:text-gray-100">Riwayat Pendidikan</a>
            </div>
        </div>

        <a class="flex items-center px-6 py-2 mt-4 text-gray-100 bg-gray-700 bg-opacity-25" href="{{ route('gaji.index') }}">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
            </svg>
            <span class="mx-3">Kenaikan Gaji</span>
            @if(isset($jumlahKenaikanGaji) && $jumlahKenaikanGaji > 0)
                <span class="ml-2 px-2 py-1 text-xs font-bold text-white bg-red-600 rounded-full">
                    {{ $jumlahKenaikanGaji }}
                </span>
            @endif
        </a>

    </nav>
</div>
