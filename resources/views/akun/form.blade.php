@extends('layouts.master')

@section('body')
<section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Add a new product</h2>
        <form action="{{ route('admin_user.store') }}" method="POST">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                    <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nama Pegawai" required="">
                </div>
                <div class="w-full">
                    <label for="nip" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIP</label>
                    <input type="text" name="nip" id="nip" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="NIP" required="">
                </div>
                <div class="w-full">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Email" required="">
                </div>
                <div>
                    <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                    <select id="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">Pilih role</option>
                        <option value="admin_sdm">Admin SDM</option>
                        <option value="admin_user">Admin User</option>
                        <option value="pegawai">Pegawai</option>
                        <option value="pemutus">Pemutus</option>
                    </select>
                </div>
                <div>
                    <label for="unit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Unit</label>
                    <select id="unit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">Pilih unit</option>
                        <option value="Subbagian Umum">Subbagian Umum</option>
                        <option value="Seksi P2">Seksi P2</option>
                        <option value="Seksi Adm Manifes">Seksi Adm Manifes</option>
                        <option value="Seksi Perbendaharaan">Seksi Perbendaharaan</option>
                        <option value="Seksi PKC I">Seksi PKC I</option>
                        <option value="Seksi PKC II">Seksi PKC II</option>
                        <option value="Seksi PKC III">Seksi PKC III</option>
                        <option value="Seksi PKC IV">Seksi PKC IV</option>
                        <option value="Seksi PKC V">Seksi PKC V</option>
                        <option value="Seksi PKC VI">Seksi PKC VI</option>
                        <option value="Seksi PKC VII">Seksi PKC VII</option>
                        <option value="Seksi PLI">Seksi PLI</option>
                        <option value="Seksi KI">Seksi KI</option>
                        <option value="Seksi PDAD">Seksi PDAD</option>
                        <option value="PFPD">PFPD</option>
                    </select>
                </div>
                <div class="w-full">
                    <label for="tanggal_naik_gaji" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">TMT</label>
                    <input type="text" name="tanggal_naik_gaji" id="tanggal_naik_gaji" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="TMT" required="">
                </div>
                <div>
                    <label for="gol" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Golongan</label>
                    <select id="gol" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">Pilih golongan</option>
                        <option value="II/a">II/a</option>
                        <option value="Seksi P2">Seksi P2</option>
                        <option value="Seksi Adm Manifes">Seksi Adm Manifes</option>
                        <option value="Seksi Perbendaharaan">Seksi Perbendaharaan</option>
                        <option value="Seksi PKC I">Seksi PKC I</option>
                        <option value="Seksi PKC II">Seksi PKC II</option>
                        <option value="Seksi PKC III">Seksi PKC III</option>
                        <option value="Seksi PKC IV">Seksi PKC IV</option>
                        <option value="Seksi PKC V">Seksi PKC V</option>
                        <option value="Seksi PKC VI">Seksi PKC VI</option>
                        <option value="Seksi PKC VII">Seksi PKC VII</option>
                        <option value="Seksi PLI">Seksi PLI</option>
                        <option value="Seksi KI">Seksi KI</option>
                        <option value="Seksi PDAD">Seksi PDAD</option>
                        <option value="PFPD">PFPD</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                    <textarea id="description" rows="8" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Your description here"></textarea>
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-4 sm:mt-6 text-sm font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                Add product
            </button>
        </form>
    </div>
  </section>
@endsection