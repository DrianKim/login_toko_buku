<div class="modal fade" id="showBukuModal{{ $buku->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <!-- Header dengan gradient -->
            <div class="modal-header border-0 bg-gradient text-white position-relative overflow-hidden"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="modal-title fw-bold fs-4 mb-0">Detail Buku</h5>
                <button type="button" class="btn-close btn-close-white opacity-75" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <!-- Decorative element -->
                <div class="position-absolute top-0 end-0 opacity-10"
                    style="font-size: 8rem; line-height: 1; margin-top: -2rem; margin-right: -1rem;">📚</div>
            </div>

            <!-- Body -->
            <div class="modal-body p-4">
                <div class="row g-4">

                    <!-- Cover Section -->
                    <div class="col-lg-4 text-center">
                        @if ($buku->cover_buku)
                            <img src="{{ asset('storage/' . $buku->cover_buku) }}" alt="Cover {{ $buku->judul_buku }}"
                                class="img-fluid rounded-3 shadow-sm border" style="max-height: 280px; width: auto;">
                        @else
                            <div class="bg-light border rounded-3 d-flex align-items-center justify-content-center text-muted"
                                style="width: 180px; height: 240px; font-size: 1.1rem;">
                                <div class="text-center">
                                    <div class="mb-2 opacity-50" style="font-size: 3rem;">📖</div>
                                    <small>Tidak ada cover</small>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Detail Section -->
                    <div class="col-lg-8">
                        <h4 class="text-primary fw-bold mb-3">{{ $buku->judul_buku }}</h4>
                        <div class="mb-3">
                            <div class="row mb-2">
                                <div class="col-5 text-secondary fw-semibold">Kode Buku</div>
                                <div class="col-7">{{ $buku->kode_buku }}</div>
                            </div>
                            {{-- <div class="row mb-2">
                                <div class="col-5 text-secondary fw-semibold">Judul Buku</div>
                                <div class="col-7">{{ $buku->judul_buku }}</div>
                            </div> --}}
                            <div class="row mb-2">
                                <div class="col-5 text-secondary fw-semibold">Penerbit</div>
                                <div class="col-7">{{ $buku->penerbit }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-secondary fw-semibold">Pengarang</div>
                                <div class="col-7">{{ $buku->pengarang }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-secondary fw-semibold">Tahun Terbit</div>
                                <div class="col-7">{{ $buku->tahun_terbit }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-secondary fw-semibold">Kategori</div>
                                <div class="col-7">{{ $buku->Tbkategori->kategori ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-secondary fw-semibold">Stok</div>
                                <div class="col-7">{{ $buku->Tbdetail->stok ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-5 text-secondary fw-semibold">Harga</div>
                                <div class="col-7">{{ $buku->Tbdetail->harga ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-outline-secondary px-4 py-2 fw-semibold" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>
