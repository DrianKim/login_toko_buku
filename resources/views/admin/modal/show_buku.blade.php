<div id="showBukuModal{{ $buku->id }}"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">

    <div class="bg-white w-full max-w-3xl rounded-2xl shadow-xl overflow-hidden relative animate-fadeIn">
        <!-- Header -->
        <div
            class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-4 relative overflow-hidden flex items-center justify-between">
            <h5 class="text-lg font-semibold">Detail Buku</h5>
            <button type="button"
                class="text-white/80 hover:text-white transition text-xl"
                data-modal-close="showBukuModal{{ $buku->id }}">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <!-- Dekorasi emoji 📚 -->
            <div class="absolute top-0 right-0 text-white/10 text-8xl leading-none select-none">📚</div>
        </div>

        <!-- Body -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Cover Section -->
            <div class="flex justify-center">
                @if ($buku->cover_buku)
                    <img src="{{ asset('storage/' . $buku->cover_buku) }}" alt="Cover {{ $buku->judul_buku }}"
                        class="rounded-lg shadow-md border max-h-72 w-auto object-contain">
                @else
                    <div
                        class="w-44 h-60 bg-gray-100 border rounded-lg flex flex-col items-center justify-center text-gray-500">
                        <div class="text-5xl mb-2 opacity-40">📖</div>
                        <span class="text-sm">Tidak ada cover</span>
                    </div>
                @endif
            </div>

            <!-- Detail Section -->
            <div class="md:col-span-2">
                <h4 class="text-indigo-600 font-semibold text-xl mb-4">{{ $buku->judul_buku }}</h4>

                <div class="space-y-2 text-sm">
                    <div class="flex">
                        <span class="w-1/3 text-gray-500 font-medium">Kode Buku</span>
                        <span class="w-2/3">{{ $buku->kode_buku }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 text-gray-500 font-medium">Penerbit</span>
                        <span class="w-2/3">{{ $buku->penerbit }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 text-gray-500 font-medium">Pengarang</span>
                        <span class="w-2/3">{{ $buku->pengarang }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 text-gray-500 font-medium">Tahun Terbit</span>
                        <span class="w-2/3">{{ $buku->tahun_terbit }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 text-gray-500 font-medium">Kategori</span>
                        <span class="w-2/3">{{ $buku->Tbkategori->kategori ?? '-' }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 text-gray-500 font-medium">Stok</span>
                        <span class="w-2/3">{{ $buku->Tbdetail->stok ?? '-' }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-1/3 text-gray-500 font-medium">Harga</span>
                        <span class="w-2/3">Rp {{ number_format($buku->Tbdetail->harga ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-6 py-3 flex justify-end">
            <button type="button"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition"
                data-modal-close="showBukuModal{{ $buku->id }}">
                Tutup
            </button>
        </div>
    </div>
</div>
