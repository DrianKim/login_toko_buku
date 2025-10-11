<div id="tambahStokModal{{ $buku->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-white/20 backdrop-blur-sm backdrop-saturate-150 transition-all">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Tambah Stok Buku</h2>

        <form action="{{ route('admin.detail-buku.tambah-stok', $buku->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Stok Saat Ini</label>
                <input type="number" value="{{ $buku->Tbdetail->stok ?? 0 }}" readonly
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-gray-600">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Tambah Stok Baru</label>
                <input type="number" name="stok_baru" min="1" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg text-sm font-medium"
                    onclick="document.getElementById('tambahStokModal{{ $buku->id }}').classList.add('hidden')">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
