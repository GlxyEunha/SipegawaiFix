@extends('layouts.master')

@section('body')
    <h3 class="text-gray-700 text-3xl font-medium">Riwayat Tugas</h3>

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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
