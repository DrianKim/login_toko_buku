<div id="editStokModal{{ $buku->id }}"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-white/20 backdrop-blur-sm backdrop-saturate-150 transition-all">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Edit Stok Buku</h2>

        <form action="{{ route('admin.detail-buku.edit-stok', $buku->id) }}" method="POST">
            @csrf

            <!-- Stok Saat Ini -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Stok Saat Ini</label>
                <input type="number" value="{{ $buku->Tbdetail->stok ?? 0 }}" readonly
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-gray-600">
            </div>

            <!-- Pilihan Aksi -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Pilih Aksi</label>
                <select name="aksi" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option hidden value="">-- Pilih Aksi --</option>
                    <option value="tambah">Tambah Stok</option>
                    <option value="kurang">Kurangi Stok</option>
                </select>
            </div>

            <!-- Input Jumlah Perubahan -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Jumlah</label>
                <input type="number" name="jumlah" min="1" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Masukkan jumlah stok">
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg text-sm font-medium"
                    onclick="document.getElementById('editStokModal{{ $buku->id }}').classList.add('hidden')">
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
