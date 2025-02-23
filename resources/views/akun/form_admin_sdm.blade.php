@extends('layouts.master')

@section('body')
@if (session('success'))
    <div class="mt-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif
<h3 class="text-gray-700 text-3xl font-medium">Form Tambah Pegawai</h3>
<br>
<section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
        <form action="{{ route('admin_sdm.store') }}" method="POST">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                    <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Nama Pegawai" required="">
                </div>
                <div class="sm:col-span-2">
                    <label for="alamat" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat</label>
                    <textarea type="text" name="alamat" id="alamat" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Alamat" required="">
                    </textarea>
                </div>
                <div class="w-full">
                    <label for="nip" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIP</label>
                    <input type="text" name="nip" id="nip" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="NIP" required="">
                </div>
                <div class="w-full">
                    <label for="no_hp" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor Telepon Pegawai</label>
                    <input type="text" name="no_hp" id="no_hp" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="No Telepon Pegawai" required="">
                </div>
                <div class="w-full">
                    <label for="no_darurat" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor Telepon Darurat</label>
                    <input type="text" name="no_darurat" id="no_darurat" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="No Telepon Darurat" required="">
                </div>
                <div>
                    <label for="jabatan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jabatan</label>
                    <select id="jabatan" name="jabatan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">Pilih jabatan</option>
                        <option value="Kepala Kantor">Kepala Kantor</option>
                        <option value="Pejabat Pengawas">Pejabat Pengawas</option>
                        <option value="PBC Ahli Muda">PBC Ahli Muda</option>
                        <option value="PBC Ahli Pertama">PBC Ahli Pertama</option>
                        <option value="PBC Mahir">PBC Mahir</option>
                        <option value="PBC Terampil">PBC Terampil</option>
                        <option value="Pranata Keuangan APBN Terampil">Pranata Keuangan APBN Terampil</option>
                        <option value="Pelaksana Pemeriksa">Pelaksana Pemeriksa</option>
                    </select>
                </div>
                <div>
                    <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                    <select id="role" name="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">Pilih role</option>
                        <option value="admin_sdm">Admin SDM</option>
                        <option value="admin_user">Admin User</option>
                        <option value="pegawai">Pegawai</option>
                        <option value="pemutus">Pemutus</option>
                    </select>
                </div>
                <div>
                    <label for="unit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Unit</label>
                    <select id="unit" name="unit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
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
                    <input type="date" name="tanggal_naik_gaji" id="tanggal_naik_gaji" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="TMT" required="">
                </div>
                <div>
                    <label for="gol" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Golongan</label>
                    <select id="gol" name="gol" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">Pilih golongan</option>
                        <option value="II/a">II/a</option>
                        <option value="II/b">II/b</option>
                        <option value="II/c">II/c</option>
                        <option value="II/d">II/d</option>
                        <option value="III/a">III/a</option>
                        <option value="III/b">III/b</option>
                        <option value="III/c">III/c</option>
                        <option value="III/d">III/d</option>
                        <option value="IV/a">IV/a</option>
                        <option value="IV/b">IV/b</option>
                    </select>
                </div>
                <div class="w-full">
                    <label for="kode_jabatan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Jabatan</label>
                    <input type="number" name="kode_jabatan" id="kode_jabatan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Kode Jabatan" required="">
                </div>
                <div class="w-full">
                    <label for="kode_unit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Unit</label>
                    <input type="number" name="kode_unit" id="kode_unit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Kode Unit" required="">
                </div>
                <div class="w-full">
                    <label for="bidang_tugas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bidang Tugas</label>
                    <input type="text" name="bidang_tugas" id="bidang_tugas" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Bidang Tugas" required="">
                </div>
                <div class="w-full">
                    <label for="periode_unit_bln" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Periode Unit Bulan</label>
                    <input type="number" name="periode_unit_bln" id="periode_unit_bln" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Periode Unit Bulan" required="">
                </div>
                <div class="w-full">
                    <label for="lama_tg_mas_th" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Lama di TG Emas</label>
                    <input type="number" name="lama_tg_mas_th" id="lama_tg_mas_th" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Lama di TG Emas" required="">
                </div>
                <div>
                    <label for="pendidikan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pendidikan</label>
                    <select id="pendidikan" name="pendidikan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">Pilih pendidikan</option>
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA">SMA</option>
                        <option value="D1">D1</option>
                        <option value="D3">D3</option>
                        <option value="D4">D4</option>
                        <option value="S1">S1</option>
                        <option value="S2">S2</option>
                        <option value="S3">S3</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Kelamin</label>
                    <div class="flex flex-col space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="jenis_kelamin" value="Laki-laki" class="form-radio text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-500" required>
                            <span class="ml-2 text-gray-900 dark:text-white">Laki-laki</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="jenis_kelamin" value="Perempuan" class="form-radio text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-500" required>
                            <span class="ml-2 text-gray-900 dark:text-white">Perempuan</span>
                        </label>
                    </div>
                </div>
                <div class="w-full">
                    <label for="tanggal_lahir" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Tanggal Lahir" required="">
                </div>
                <div>
                    <label for="agama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agama</label>
                    <select id="agama" name="agama" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">Pilih agama</option>
                        <option value="Islam">Islam</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katholik">Katholik</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Budha">Budha</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-5 py-2.5 mt-6 mb-4 text-sm font-medium text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-blue-700">
                Submit
            </button>
        </form>
    </div>
  </section>
@endsection