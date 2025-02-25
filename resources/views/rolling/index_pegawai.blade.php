@extends('layouts.master')

@section('body')
    @if (session('success'))
        <div class="mt-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    <h3 class="text-gray-700 text-3xl font-medium">Rolling Pegawai</h3>

    <br>
    <div class="flex justify-between items-center w-full">
        <div class="flex items-left">
            {{-- Search & Filter --}}
            <form id="search-form" method="GET" action="{{ route('admin_sdm.rolling.search_filter') }}" class="flex items-center">
                {{-- Search --}}
                <div class="relative mx-4 lg:mx-0">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 fill-gray-500">
                            <path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" id="search" name="search" value="{{ request()->get('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs font-medium rounded-lg focus:ring-blue-500 focus:border-blue-500 block ps-10 p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari Nama/NIP/Unit">
                </div>

                {{-- Filter by Golongan --}}
                <select id="gol" name="gol" class="ml-2 w-48 p-3 text-xs font-medium text-gray-500 border ps-3 border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" onchange="this.form.submit()">
                    <option value="" {{ request()->get('gol') == '' ? 'selected' : '' }}>Golongan</option>
                    <option value="semua" {{ request()->get('gol') == 'semua' ? 'selected' : '' }}>Semua Golongan</option>
                    <option value="II/a" {{ request()->get('gol') == 'II/a' ? 'selected' : '' }}>II/a</option>
                    <option value="II/b" {{ request()->get('gol') == 'II/b' ? 'selected' : '' }}>II/b</option>
                    <option value="II/c" {{ request()->get('gol') == 'II/c' ? 'selected' : '' }}>II/c</option>
                    <option value="II/d" {{ request()->get('gol') == 'II/d' ? 'selected' : '' }}>II/d</option>
                    <option value="III/a" {{ request()->get('gol') == 'III/a' ? 'selected' : '' }}>III/a</option>
                    <option value="III/b" {{ request()->get('gol') == 'III/b' ? 'selected' : '' }}>III/b</option>
                    <option value="III/c" {{ request()->get('gol') == 'III/c' ? 'selected' : '' }}>III/c</option>
                    <option value="III/d" {{ request()->get('gol') == 'III/d' ? 'selected' : '' }}>III/d</option>
                    <option value="IV/a" {{ request()->get('gol') == 'IV/a' ? 'selected' : '' }}>IV/a</option>
                    <option value="IV/b" {{ request()->get('gol') == 'IV/b' ? 'selected' : '' }}>IV/b</option>
                    <option value="IV/c" {{ request()->get('gol') == 'IV/c' ? 'selected' : '' }}>IV/c</option>
                    <option value="IV/d" {{ request()->get('gol') == 'IV/d' ? 'selected' : '' }}>IV/d</option>
                </select>

                {{-- Tombol Submit (Opsional) --}}
                <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Cari
                </button>
            </form>
        </div>
    </div>

    <div class="mt-8">
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="min-w-full shadow border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">NIP</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Golongan</th>
                                <th class="px-6 py-3 bg-gray-50">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($users as $user)
                            <tr>
                                <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ $user->nip }}</td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ $user->unit }}</td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ $user->jabatan }}</td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ $user->gol }}</td>
                                <td class="px-6 py-4 border-b border-gray-200 text-center">
                                    <button class="text-indigo-600 hover:text-indigo-900 open-modal-btn"
                                        data-id="{{ $user->nip }}"
                                        data-name="{{ $user->name }}"
                                        data-nip="{{ $user->nip }}"
                                        data-unit="{{ $user->unit }}"
                                        data-jabatan="{{ $user->jabatan }}"
                                        data-gol="{{ $user->gol }}">
                                        Rolling
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 hidden bg-gray-600 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold">Detail Pegawai</h2>
            <p id="modal-name" class="mt-2"></p>
            <p id="modal-nip" class="mt-1"></p>
            <p id="modal-unit" class="mt-1"></p>
            <p id="modal-jabatan" class="mt-1"></p>
            <p id="modal-gol" class="mt-1"></p>
            <br>
            <form id="rolling-form" action="{{ route('rollingPegawai.store') }}" method="POST">
                @csrf
                <input type="hidden" name="nip" id="modal-user-id">
                <label for="unit" class="block text-sm font-medium text-gray-700">Pilih Unit Baru</label>
                <select name="new_unit" id="new_unit" class="mt-1 block w-full p-2 border rounded">
                    <option value="Subbagian Umum">Subbagian Umum</option>
                    <option value="Seksi P2">Seksi P2</option>
                    <option value="Seksi Adm Manifes">Seksi Adm Manifes</option>
                </select>
                <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            </form>            
            <button onclick="closeModal()" class="mt-2 text-red-500">Tutup</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.open-modal-btn').forEach(button => {
                button.addEventListener('click', function() {
                    let name = this.getAttribute('data-name');
                    let nip = this.getAttribute('data-nip');
                    let unit = this.getAttribute('data-unit');
                    let jabatan = this.getAttribute('data-jabatan');
                    let gol = this.getAttribute('data-gol');
                    
                    document.getElementById('modal-name').textContent = 'Nama: ' + name;
                    document.getElementById('modal-nip').textContent = 'NIP: ' + nip;
                    document.getElementById('modal-unit').textContent = 'Unit: ' + unit;
                    document.getElementById('modal-jabatan').textContent = 'Jabatan: ' + jabatan;
                    document.getElementById('modal-gol').textContent = 'Golongan: ' + gol;
                    document.getElementById('modal-user-id').value = nip;
                    
                    document.getElementById('modal').classList.remove('hidden');
                });
            });
        });

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
@endsection
