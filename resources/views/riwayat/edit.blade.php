@extends('layouts.master')

@section('body')
@if (session('success'))
    <div class="mt-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif
<h3 class="text-gray-700 text-3xl font-medium">Form Edit Riwayat Tugas</h3>
<br>
<section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
        <form action="{{ route('pegawai.updateTugas', $tugas->id_tugas) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="w-full">
                    <label for="nama_tugas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Tugas</label>
                    <input type="text" name="nama_tugas" id="nama_tugas" value="{{ $tugas->nama_tugas }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nama Tugas" required="">
                </div>
                <div class="w-full">
                    <label for="tanggal_mulai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $tugas->tanggal_mulai }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Tanggal Mulai" required="">
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-6 mb-4 text-sm font-medium text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-blue-700">
                Submit
            </button>
        </form>
    </div>
  </section>
@endsection