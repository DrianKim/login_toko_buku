<div id="editDetailModal{{ $buku->id }}"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-white/20 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Edit Stok & Harga Buku</h2>

        <form action="{{ route('admin.detail-buku.update-detail', $buku->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Harga Sekarang</label>
                <input type="text"
                    value="{{ $buku->Tbdetail->harga ? 'Rp' . number_format($buku->Tbdetail->harga, 0, ',', '.') : 'Belum ada harga' }}"
                    readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-gray-600">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-1">Harga Baru (Rp)</label>
                <input type="number" name="harga_baru" min="0" required
                    value="{{ $buku->Tbdetail->harga ?? '' }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Stok Sekarang</label>
                <input type="number" value="{{ $buku->Tbdetail->stok ?? 0 }}" readonly
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-gray-600">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Ubah Stok Menjadi</label>
                <input type="number" name="stok_baru" min="0" required value="{{ $buku->Tbdetail->stok ?? '' }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg text-sm font-medium"
                    onclick="document.getElementById('editDetailModal{{ $buku->id }}').classList.add('hidden')">
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
