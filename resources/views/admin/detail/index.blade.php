@extends('admin.layouts.app')

@section('content')
    <h1 class="mb-4">Tambah Detail Buku</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.detail-buku.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    {{-- Dropdown Buku --}}
                    <div class="col-md-8">
                        <label class="form-label">Pilih Buku</label>
                        <select class="form-select" id="bukuSelect" name="id_buku" required>
                            <option value="">-- Pilih Judul Buku --</option>
                            @foreach ($buku as $item)
                                <option value="{{ $item->id }}"
                                    data-kode="{{ $item->kode_buku }}"
                                    data-judul="{{ $item->judul_buku }}"
                                    data-tahun="{{ $item->tahun_terbit }}"
                                    data-stok="{{ $item->stok }}"
                                    data-harga="{{ $item->harga }}"
                                    >{{ $item->judul_buku }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Input Stok & Harga --}}
                <div class="row g-3 mt-3" id="detailInputs" style="display: none;">
                    <div class="col-md-4">
                        <label class="form-label">Stok</label>
                        <input type="number" class="form-control" name="stok" id="inputStok" placeholder="Masukkan stok"
                            value="{{ old('stok') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Harga</label>
                        <input type="number" class="form-control" name="harga" id="inputHarga" placeholder="Masukkan harga"
                            value="{{ old('harga') }}" required>
                    </div>
                </div>

                {{-- Tabel Detail Buku --}}
                <div class="row mt-3" id="bukuDetailTable" style="display: none;">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Kode Buku</th>
                                    <th>Judul Buku</th>
                                    <th class="text-center">Stok</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Tahun Terbit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center" id="tableIter">1</td>
                                    <td class="text-center" id="tableKode"></td>
                                    <td id="tableJudul"></td>
                                    <td class="text-center" id="tableStok"></td>
                                    <td class="text-center" id="tableHarga"></td>
                                    <td class="text-center" id="tableTahun"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <button type="submit" class="btn btn-dark mt-3">Simpan</button>
            </form>
        </div>
    </div>

    <script>
        function updateTable() {
            document.getElementById('tableStok').textContent = document.getElementById('inputStok').value;
            document.getElementById('tableHarga').textContent = document.getElementById('inputHarga').value;
        }

        document.getElementById('bukuSelect').addEventListener('change', function() {
            const detailInputs = document.getElementById('detailInputs');
            const bukuDetailTable = document.getElementById('bukuDetailTable');
            const selected = this.options[this.selectedIndex];

            if (this.value) {
                detailInputs.style.display = 'flex';
                bukuDetailTable.style.display = 'block';

                document.getElementById('tableKode').textContent = selected.getAttribute('data-kode');
                document.getElementById('tableJudul').textContent = selected.getAttribute('data-judul');
                document.getElementById('tableTahun').textContent = selected.getAttribute('data-tahun') ?? '';

                const stok = selected.getAttribute('data-stok');
                const harga = selected.getAttribute('data-harga');
                document.getElementById('inputStok').value = stok ? stok : '';
                document.getElementById('inputHarga').value = harga ? harga : '';
                updateTable();
            } else {
                detailInputs.style.display = 'none';
                bukuDetailTable.style.display = 'none';

                document.getElementById('tableKode').textContent = '';
                document.getElementById('tableJudul').textContent = '';
                document.getElementById('tableStok').textContent = '';
                document.getElementById('tableHarga').textContent = '';
                document.getElementById('tableTahun').textContent = '';
                document.getElementById('inputStok').value = '';
                document.getElementById('inputHarga').value = '';
            }
        });

        document.getElementById('inputStok').addEventListener('input', updateTable);
        document.getElementById('inputHarga').addEventListener('input', updateTable);

        window.addEventListener('DOMContentLoaded', function() {
            const bukuSelect = document.getElementById('bukuSelect');
            if (bukuSelect.value) {
                bukuSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
