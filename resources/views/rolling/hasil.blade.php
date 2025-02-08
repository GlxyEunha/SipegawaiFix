@extends('layouts.master')

@section('body')
    @if (session('success'))
        <div class="mt-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    <h3 class="text-gray-700 text-3xl font-medium">Dashboard</h3>

    <br>
    <a href="{{ route('rolling.export') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700">
        Export to Excel
    </a>

    <div class="mt-8">
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="min-w-full shadow border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">NIP</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Unit Sekarang</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Unit Baru</th>
                                <th class="px-6 py-3 bg-gray-50">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($rollings as $r)
                            <tr>
                                <td class="px-6 py-4 border-b border-gray-200">
                                    <div class="text-sm font-medium text-gray-900">{{ $r->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $r->email }}</div>
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ $r->nip }}</td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ $r->old_unit }}</td>
                                <td class="px-6 py-4 border-b border-gray-200">{{ $r->new_unit }}</td>
                                <td class="px-6 py-4 border-b border-gray-200 text-center">
                                    {{-- <button class="approve-btn text-sm text-green-700 bg-green-100 hover:bg-green-200 rounded border border-green-500 px-2 py-1" data-nip="{{ $r->nip }}">
                                        Setujui
                                    </button> --}}
                                    <form action="{{ route('rolling.accept', ['nip' => $r->nip]) }}" method="POST">
                                        @csrf
                                        <button class="text-green-600 hover:text-green-900">
                                            Setujui
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

    <!-- Modal Konfirmasi -->
    {{-- <div class="fixed hidden inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="modal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Apakah Anda yakin ingin menyetujui pemindahan ini?</p>
                </div>
                <div class="items-center px-4 py-3 flex justify-between">
                    <form id="approval-form" method="POST" action="">
                        @csrf
                        <input type="hidden" name="nip" id="rolling-nip">
                        <button type="submit" id="yes-btn" class="px-4 py-2 bg-green-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-600 focus:outline-none">Ya</button>
                    </form>
                    <button id="no-btn" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-600 focus:outline-none">Tidak</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let modal = document.getElementById("modal");
            let approveButtons = document.querySelectorAll(".approve-btn");
            let yesButton = document.getElementById("yes-btn");
            let noButton = document.getElementById("no-btn");
            let form = document.getElementById("approval-form");
            let rollingNipInput = document.getElementById("rolling-nip");
    
            approveButtons.forEach(button => {
                button.addEventListener("click", function () {
                    let rollingNip = this.getAttribute("data-nip"); // Ambil NIP dari tombol
                    rollingNipInput.value = rollingNip;
                    form.action = `/hasil/accept/${rollingNip}`; // Ubah action form secara dinamis
                    modal.style.display = "block";
                });
            });
    
            noButton.addEventListener("click", function () {
                modal.style.display = "none";
            });
    
            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };
        });
    </script> --}}
    


@endsection
