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
        <form action="{{ route('admin_sdm.update', $user->nip) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="sm:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                    <input type="text" name="name" id="name" value="{{ $user->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required>
                </div>
                <div class="sm:col-span-2">
                    <label for="alamat" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>{{ $user->alamat }}</textarea>
                </div>
                <div class="w-full">
                    <label for="nip" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">NIP</label>
                    <input type="text" name="nip" id="nip" value="{{ $user->nip }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required disabled>
                </div>
                <div class="w-full">
                    <label for="no_hp" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor Telepon Pegawai</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ $user->no_hp }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                </div>
                <div class="w-full">
                    <label for="no_darurat" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor Telepon Darurat</label>
                    <input type="text" name="no_darurat" id="no_darurat" value="{{ $user->no_darurat }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                </div>
                <div>
                    <label for="jabatan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jabatan</label>
                    <select id="jabatan" name="jabatan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">Pilih jabatan</option>
                        <option value="Kepala Kantor" {{ $user->jabatan == 'Kepala Kantor' ? 'selected' : '' }}>Kepala Kantor</option>
                        <option value="Pejabat Pengawas" {{ $user->jabatan == 'Pejabat Pengawas' ? 'selected' : '' }}>Pejabat Pengawas</option>
                        <option value="PBC Ahli Muda" {{ $user->jabatan == 'PBC Ahli Muda' ? 'selected' : '' }}>PBC Ahli Muda</option>
                        <option value="PBC Ahli Pertama" {{ $user->jabatan == 'PBC Ahli Pertama' ? 'selected' : '' }}>PBC Ahli Pertama</option>
                        <option value="PBC Mahir" {{ $user->jabatan == 'PBC Mahir' ? 'selected' : '' }}>PBC Mahir</option>
                        <option value="PBC Terampil" {{ $user->jabatan == 'PBC Terampil' ? 'selected' : '' }}>PBC Terampil</option>
                        <option value="Pranata Keuangan APBN Terampil" {{ $user->jabatan == 'Pranata Keuangan APBN Terampil' ? 'selected' : '' }}>Pranata Keuangan APBN Terampil</option>
                        <option value="Pelaksana Pemeriksa" {{ $user->jabatan == 'Pelaksana Pemeriksa' ? 'selected' : '' }}>Pelaksana Pemeriksa</option>
                    </select>
                </div>
                <div>
                    <label for="role" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                    <select name="role" id="role" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        <option selected="">Pilih role</option>
                        <option value="admin_sdm" {{ $user->role == 'admin_sdm' ? 'selected' : '' }}>Admin SDM</option>
                        <option value="admin_user" {{ $user->role == 'admin_user' ? 'selected' : '' }}>Admin User</option>
                        <option value="pegawai" {{ $user->role == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                        <option value="pemutus" {{ $user->role == 'pemutus' ? 'selected' : '' }}>Pemutus</option>
                    </select>
                </div>
                <div>
                    <label for="unit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Unit</label>
                    <select id="unit" name="unit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">Pilih unit</option>
                        <option value="Subbagian Umum" {{ $user->unit == 'Subbagian Umum' ? 'selected' : '' }}>Subbagian Umum</option>
                        <option value="Seksi P2" {{ $user->unit == 'Seksi P2' ? 'selected' : '' }}>Seksi P2</option>
                        <option value="Seksi Adm Manifes" {{ $user->unit == 'Seksi Adm Manifes' ? 'selected' : '' }}>Seksi Adm Manifes</option>
                        <option value="Seksi Perbendaharaan" {{ $user->unit == 'Seksi Perbendaharaan' ? 'selected' : '' }}>Seksi Perbendaharaan</option>
                        <option value="Seksi PKC I" {{ $user->unit == 'Seksi PKC I' ? 'selected' : '' }}>Seksi PKC I</option>
                        <option value="Seksi PKC II" {{ $user->unit == 'Seksi PKC II' ? 'selected' : '' }}>Seksi PKC II</option>
                        <option value="Seksi PKC III" {{ $user->unit == 'Seksi PKC III' ? 'selected' : '' }}>Seksi PKC III</option>
                        <option value="Seksi PKC IV" {{ $user->unit == 'Seksi PKC IV' ? 'selected' : '' }}>Seksi PKC IV</option>
                        <option value="Seksi PKC V" {{ $user->unit == 'Seksi PKC V' ? 'selected' : '' }}>Seksi PKC V</option>
                        <option value="Seksi PKC VI" {{ $user->unit == 'Seksi PKC VI' ? 'selected' : '' }}>Seksi PKC VI</option>
                        <option value="Seksi PKC VII" {{ $user->unit == 'Seksi PKC VII' ? 'selected' : '' }}>Seksi PKC VII</option>
                        <option value="Seksi PLI" {{ $user->unit == 'Seksi PLI' ? 'selected' : '' }}>Seksi PLI</option>
                        <option value="Seksi KI" {{ $user->unit == 'Seksi KI' ? 'selected' : '' }}>Seksi KI</option>
                        <option value="Seksi PDAD" {{ $user->unit == 'Seksi PDAD' ? 'selected' : '' }}>Seksi PDAD</option>
                        <option value="PFPD" {{ $user->unit == 'PFPD' ? 'selected' : '' }}>PFPD</option>
                    </select>
                </div>
                <div class="w-full">
                    <label for="tanggal_naik_gaji" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">TMT</label>
                    <input type="date" name="tanggal_naik_gaji" id="tanggal_naik_gaji" value="{{ $user->tanggal_naik_gaji }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required>
                </div>
                <div>
                    <label for="gol" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Golongan</label>
                    <select id="gol" name="gol" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">Pilih golongan</option>
                        <option value="II/a" {{ $user->gol == 'II/a' ? 'selected' : '' }}>II/a</option>
                        <option value="II/b" {{ $user->gol == 'II/b' ? 'selected' : '' }}>II/b</option>
                        <option value="II/c" {{ $user->gol == 'II/c' ? 'selected' : '' }}>II/c</option>
                        <option value="II/d" {{ $user->gol == 'II/d' ? 'selected' : '' }}>II/d</option>
                        <option value="III/a" {{ $user->gol == 'III/a' ? 'selected' : '' }}>III/a</option>
                        <option value="III/b" {{ $user->gol == 'III/b' ? 'selected' : '' }}>III/b</option>
                        <option value="III/c" {{ $user->gol == 'III/c' ? 'selected' : '' }}>III/c</option>
                        <option value="III/d" {{ $user->gol == 'III/d' ? 'selected' : '' }}>III/d</option>
                        <option value="IV/a" {{ $user->gol == 'IV/a' ? 'selected' : '' }}>IV/a</option>
                        <option value="IV/b" {{ $user->gol == 'IV/b' ? 'selected' : '' }}>IV/b</option>
                    </select>
                </div>
                <div class="w-full">
                    <label for="kode_jabatan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Jabatan</label>
                    <input type="number" name="kode_jabatan" id="kode_jabatan" value="{{ $user->kode_jabatan }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required>
                </div>
                <div class="w-full">
                    <label for="kode_unit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode Unit</label>
                    <input type="number" name="kode_unit" id="kode_unit" value="{{ $user->kode_unit }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                </div>
                <div class="w-full">
                    <label for="bidang_tugas" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bidang Tugas</label>
                    <input type="text" name="bidang_tugas" id="bidang_tugas" value="{{ $user->bidang_tugas }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                </div>
                <div class="w-full">
                    <label for="periode_unit_bln" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Periode Unit Bulan</label>
                    <input type="number" name="periode_unit_bln" id="periode_unit_bln" value="{{ $user->periode_unit_bln }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                </div>
                <div class="w-full">
                    <label for="lama_tg_mas_th" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Lama di TG Emas</label>
                    <input type="number" name="lama_tg_mas_th" id="lama_tg_mas_th" value="{{ $user->lama_tg_mas_th }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                </div>
                <div>
                    <label for="pendidikan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pendidikan</label>
                    <select id="pendidikan" name="pendidikan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">Pilih pendidikan</option>
                        <option value="SD" {{ $user->pendidikan == 'SD' ? 'selected' : '' }}>SD</option>
                        <option value="SMP" {{ $user->pendidikan == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ $user->pendidikan == 'SMA' ? 'selected' : '' }}>SMA</option>
                        <option value="D1" {{ $user->pendidikan == 'D1' ? 'selected' : '' }}>D1</option>
                        <option value="D3" {{ $user->pendidikan == 'D3' ? 'selected' : '' }}>D3</option>
                        <option value="D4" {{ $user->pendidikan == 'D4' ? 'selected' : '' }}>D4</option>
                        <option value="S1" {{ $user->pendidikan == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ $user->pendidikan == 'S2' ? 'selected' : '' }}>S2</option>
                        <option value="S3" {{ $user->pendidikan == 'S3' ? 'selected' : '' }}>S3</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Kelamin</label>
                    <div class="flex flex-col space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="jenis_kelamin" value="Laki-laki" class="form-radio text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-500" {{ $user->jenis_kelamin == 'Laki-laki' ? 'checked' : '' }} required>
                            <span class="ml-2 text-gray-900 dark:text-white">Laki-laki</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="jenis_kelamin" value="Perempuan" class="form-radio text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-500" {{ $user->jenis_kelamin == 'Perempuan' ? 'checked' : '' }} required>
                            <span class="ml-2 text-gray-900 dark:text-white">Perempuan</span>
                        </label>
                    </div>
                </div>
                <div class="w-full">
                    <label for="tanggal_lahir" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ $user->tanggal_lahir }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                </div>
                <div>
                    <label for="agama" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agama</label>
                    <select id="agama" name="agama" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="">Pilih agama</option>
                        <option value="Islam" {{ $user->agama == 'Islam' ? 'selected' : '' }}>Islam</option>
                        <option value="Kristen" {{ $user->agama == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                        <option value="Katholik" {{ $user->agama == 'Katholik' ? 'selected' : '' }}>Katholik</option>
                        <option value="Hindu" {{ $user->agama == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                        <option value="Budha" {{ $user->agama == 'Budha' ? 'selected' : '' }}>Budha</option>
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