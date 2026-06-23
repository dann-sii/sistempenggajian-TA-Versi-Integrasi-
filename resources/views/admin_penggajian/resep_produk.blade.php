@extends('layouts.app')

@section('title', 'Data Produk')

@section('content')
<div class="flex flex-col space-y-8 overflow-hidden">
    <div class="space-y-6">
        <h1 class="text-2xl font-black uppercase tracking-tight text-carbon-black-900 border-l-4 border-hunter-green-500 pl-3 py-0.5">
            Data Produk
        </h1>

        <div class="flex justify-end items-end">
            <button class="bg-black-forest-700 text-white px-6 h-[42px] text-sm font-bold rounded-lg shadow-md hover:bg-black-forest-800 transition-all duration-200 flex items-center space-x-2 active:scale-95 group uppercase tracking-widest mb-0.5">
                <x-tabler-plus class="w-5 h-5" stroke-width="3" />
                <span>Tambah</span>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-none shadow-xl border border-carbon-black-200 overflow-hidden relative flex-1 min-h-0">
        <div class="max-h-[calc(125vh-24rem)] overflow-y-auto scrollbar-invisible">
            <table class="w-full border-collapse">
                <thead class="sticky top-0 z-10 bg-carbon-black-50 border-b border-carbon-black-200 text-carbon-black-600">
                    <tr>
                        <th class="px-5 py-4 text-sm font-black uppercase tracking-wider text-center w-16">NO</th>
                        <th class="px-5 py-4 text-sm font-black uppercase tracking-wider text-left">NAMA PRODUK</th>
                        <th class="px-5 py-4 text-sm font-black uppercase tracking-wider text-center">JUMLAH STANDAR</th>
                        <th class="px-5 py-4 text-sm font-black uppercase tracking-wider text-center">SUSU</th>
                        <th class="px-5 py-4 text-sm font-black uppercase tracking-wider text-center">GULA</th>
                        <th class="px-5 py-4 text-sm font-black uppercase tracking-wider text-center">PERASA</th>
                        <th class="px-5 py-4 text-sm font-black uppercase tracking-wider text-center">STATUS</th>
                        <th class="px-5 py-4 text-sm font-black uppercase tracking-wider text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-carbon-black-100">
                    @foreach($produkResep as $index => $produk)
                        <tr class="hover:bg-hunter-green-50 transition-colors duration-150 group">
                            <td class="px-5 py-5 text-center text-base font-bold text-hunter-green-700 bg-carbon-black-50/50 w-16">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-5 py-5">
                                <p class="text-base font-semibold text-carbon-black-900 group-hover:text-black-forest-700">{{ $produk['nama_produk'] }}</p>
                            </td>
                            <td class="px-5 py-5 text-center text-base font-semibold text-carbon-black-800">
                                {{ number_format($produk['jumlah_standar'], 0, ',', '.') }} {{ $produk['satuan_produk'] }}
                            </td>
                            <td class="px-5 py-5 text-center text-base font-semibold text-carbon-black-800">
                                {{ number_format($produk['susu_liter'], 2, ',', '.') }} Liter
                            </td>
                            <td class="px-5 py-5 text-center text-base font-semibold text-carbon-black-800">
                                {{ number_format($produk['gula_gram'], 0, ',', '.') }} Gram
                            </td>
                            <td class="px-5 py-5 text-center text-base font-semibold text-carbon-black-800">
                                {{ $produk['perasa_jenis'] ?? '-' }}
                                <span class="block text-xs font-semibold text-carbon-black-400">
                                    {{ number_format($produk['perasa_gram'], 0, ',', '.') }} Gram
                                </span>
                            </td>
                            <td class="px-5 py-5 text-center text-base">
                                @php
                                    $statusColor = strtolower($produk['status']) === 'aktif'
                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                        : 'bg-rose-50 text-rose-700 border-rose-200';
                                @endphp
                                <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm {{ $statusColor }}">
                                    {{ $produk['status'] }}
                                </span>
                            </td>
                            <td class="px-5 py-5 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-amber-200" title="Lihat">
                                        <x-tabler-eye class="w-4 h-4" />
                                        <span>Detail</span>
                                    </button>
                                    <button class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-blue-200" title="Edit">
                                        <x-tabler-pencil class="w-4 h-4" />
                                        <span>Ubah</span>
                                    </button>
                                    <button class="flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all shadow-sm group/btn text-xs font-bold uppercase tracking-wider border border-red-200" title="Hapus">
                                        <x-tabler-trash class="w-4 h-4" />
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
