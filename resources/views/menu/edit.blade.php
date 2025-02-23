@extends('layouts.master')

@section('body')
@if (session('success'))
    <div class="mt-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif
<h3 class="text-gray-700 text-3xl font-medium">Form Edit Menu</h3>
<br>
<section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
        <form action="{{ route('admin_sdm.atur_menu.update', $menu->nip) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Pegawai</label>
                    <input type="text" name="name" id="name" value="{{ $menu->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nama Pegawai" disabled>
                </div>
                <div class="sm:col-span-2">
                    <label for="menu" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Menu</label>
                    <fieldset>
                        <legend class="sr-only">Checkbox variants</legend>
                      
                        <div class="flex items-center mb-4">
                            <input type="hidden" name="dashboard" value="0">
                            <input id="dashboard" name="dashboard" type="checkbox" value="1" {{ $menu->dashboard ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="dashboard" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Dashboard</label>
                        </div>

                        <div class="flex items-center mb-4">
                            <input type="hidden" name="daftar_pegawai" value="0">
                            <input id="daftar_pegawai" name="daftar_pegawai" type="checkbox" value="1" {{ $menu->daftar_pegawai ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="daftar_pegawai" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Daftar Pegawai</label>
                        </div>

                        <div class="flex items-center mb-4">
                            <input type="hidden" name="tambah_pengguna" value="0">
                            <input id="tambah_pengguna" name="tambah_pengguna" type="checkbox" value="1" {{ $menu->tambah_pengguna ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="tambah_pengguna" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tambah Pengguna</label>
                        </div>

                        <div class="flex items-center mb-4">
                            <input type="hidden" name="rolling" value="0">
                            <input id="rolling" name="rolling" type="checkbox" value="1" {{ $menu->rolling ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="rolling" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Rolling</label>
                        </div>

                        <div class="flex items-center mb-4">
                            <input type="hidden" name="riwayat" value="0">
                            <input id="riwayat" name="riwayat" type="checkbox" value="1" {{ $menu->riwayat ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="riwayat" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Riwayat</label>
                        </div>

                        <div class="flex items-center mb-4">
                            <input type="hidden" name="gaji" value="0">
                            <input id="gaji" name="gaji" type="checkbox" value="1" {{ $menu->gaji ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="gaji" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Gaji</label>
                        </div>
                    
                      </fieldset>
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-6 mb-4 text-sm font-medium text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-blue-700">
                Submit
            </button>
        </form>
    </div>
  </section>
@endsection