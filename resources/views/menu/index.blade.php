@extends('layouts.master')

@section('body')
    <h3 class="text-gray-700 text-3xl font-medium">Atur Menu</h3>

    <div class="mt-8">
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="min-w-full shadow border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 bg-gray-50">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($menu as $m)
                            <tr>
                                <td class="px-6 py-4 border-b border-gray-200">
                                <div class="text-sm leading-5 font-medium text-gray-900">{{ $m->name }}</div>
                                        <div class="text-sm leading-5 text-gray-500">{{ $m->email }}</div>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200 text-center">
                                    <a href="{{ route('admin_sdm.atur_menu.edit', $m->nip) }}" class="px-3 py-1 bg-blue-500 text-white text-xs font-semibold rounded">Edit</a>
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
