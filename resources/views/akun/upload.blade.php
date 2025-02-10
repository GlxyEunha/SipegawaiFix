@extends('layouts.master')

@section('body')
<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">

        {{-- Pesan Sukses --}}
        @if (session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-100 dark:bg-gray-800 dark:text-green-400" role="alert">
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        @endif

        {{-- Pesan Error --}}
        @if (session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400" role="alert">
            <span class="font-medium">{{ session('error') }}</span>
        </div>
        @endif

        <h1 class="text-2xl mb-5 font-bold text-gray-900 dark:text-white">
            Generate Akun Pegawai
        </h1>

        {{-- Form Import --}}
        <form action="{{ route('admin_user.impor') }}" method="POST" enctype="multipart/form-data" class="mb-6">
            @csrf
            <div class="flex items-center space-x-4">
                <input type="file" name="file" class="border border-gray-300 p-2 rounded-lg focus:ring focus:ring-blue-300">
                <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-400 dark:hover:bg-blue-500" type="submit">
                    Import Data Pegawai
                </button>
            </div>
        </form>

        {{-- Tabel Data Pegawai yang Baru Diimpor --}}
        @if(session()->has('pegawaiData') && count(session('pegawaiData')) > 0)
        <div class="overflow-x-auto shadow-md sm:rounded-lg">
            <h2 class="text-lg font-semibold mb-4">Data Pegawai yang Baru Diimpor</h2>
            <table class="w-full text-sm text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">NIP</th>
                        <th scope="col" class="px-6 py-3">Nama</th>
                        <th scope="col" class="px-6 py-3">Jabatan</th>
                        <th scope="col" class="px-6 py-3">Unit</th>
                        <th scope="col" class="px-6 py-3">Golongan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (session('pegawaiData') as $pegawai)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $pegawai->nip }}</td>
                        <td class="px-6 py-4">{{ $pegawai->name }}</td>
                        <td class="px-6 py-4">{{ $pegawai->jabatan }}</td>
                        <td class="px-6 py-4">{{ $pegawai->unit }}</td>
                        <td class="px-6 py-4">{{ $pegawai->gol }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Tombol Generate Akun --}}
        <div class="mt-6 flex justify-end">
            <form action="{{ route('admin_user.generateAccounts') }}" method="POST">
                @csrf
                <button class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm px-5 py-2.5">
                    Generate Akun
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
