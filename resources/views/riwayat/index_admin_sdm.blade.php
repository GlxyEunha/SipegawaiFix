@extends('layouts.master')

@section('body')
    <h3 class="text-gray-700 text-3xl font-medium">Riwayat Tugas</h3>
    <br>
    <div class="flex justify-between items-center w-full">
        <div class="flex items-left">
            {{-- Search & Filter --}}
            <form id="search-form" method="GET" action="{{ route('admin_sdm.daftar_tugas') }}" class="flex items-center">
                {{-- Search --}}
                <div class="relative mx-4 lg:mx-0">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 fill-gray-500">
                            <path fill-rule="evenodd" d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" id="search" name="search" value="{{ request()->get('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs font-medium rounded-lg focus:ring-blue-500 focus:border-blue-500 block ps-10 p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari Nama/NIP/Tugas">
                </div>
                {{-- Tombol Submit (Opsional) --}}
                <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Cari
                </button>
            </form>
        </div> 
        <a href="{{ route('admin_sdm.riwayatTugas') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700">
            Tambah Riwayat Tugas
        </a>
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
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Tugas</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Mulai</th>
                                <th class="px-6 py-3 bg-gray-50">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($tugas as $t)
                            <tr>
                                <td class="px-6 py-4 border-b border-gray-200">
                                <div class="text-sm leading-5 font-medium text-gray-900">{{ $t->name }}</div>
                                        <div class="text-sm leading-5 text-gray-500">{{ $t->email }}</div>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ $t->nip }}</td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ $t->nama_tugas }}</td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ $t->tanggal_mulai }}</td>
                                <td class="px-6 py-4 border-b border-gray-200 text-center">
                                    <a href="{{ route('admin_sdm.editTugas', $t->id_tugas) }}" class="px-3 py-1 bg-blue-500 text-white text-xs font-semibold rounded">Edit</a>
                                    <form action="{{ route('admin_sdm.destroyTugas', $t->id_tugas) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
