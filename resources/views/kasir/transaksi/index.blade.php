@extends('kasir.layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-6 py-10">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-blue-700"><i class="fas fa-cart-shopping"></i> Keranjang Belanja</h1>
            <a href="#"
                class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition font-medium flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Buku
            </a>
        </div>

        @if (empty($cart))
            <div class="p-6 bg-blue-100 text-blue-600 rounded-lg">
                Keranjang masih kosong. Silakan tambahkan buku dari data buku.
            </div>
        @else
            <div class="bg-white shadow-lg rounded-2xl border border-blue-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-blue-100 text-blue-800 uppercase text-xs font-semibold">
                            <tr>
                                <th class="px-6 py-3 text-center">#</th>
                                <th class="px-6 py-3">Judul Buku</th>
                                <th class="px-6 py-3 text-center">Harga</th>
                                <th class="px-6 py-3 text-center">Qty</th>
                                <th class="px-6 py-3 text-center">Subtotal</th>
                                <th class="px-6 py-3 text-center"><i class="fas fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach ($cart as $id => $item)
                                @php
                                    $subtotal = $item['qty'] * $item['harga'];
                                    $total += $subtotal;
                                @endphp
                                <tr class="border-b hover:bg-blue-50 transition">
                                    <td class="px-6 py-3 text-center">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-3 flex items-center gap-3">
                                        <img src="{{ isset($item['cover_buku']) && $item['cover_buku'] ? asset('storage/' . $item['cover_buku']) : 'https://via.placeholder.com/50x70' }}"
                                            alt="{{ $item['judul_buku'] }}" class="w-12 h-16 object-cover rounded">
                                        <span>{{ $item['judul_buku'] }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-center">Rp {{ number_format($item['harga'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        <form action="{{ route('kasir.transaksi.update', $id) }}" method="POST"
                                            class="flex items-center justify-center update-cart-form"
                                            data-stok="{{ $item['stok'] }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button"
                                                class="px-3 cursor-pointer btn-minus rounded bg-gray-200 hover:bg-gray-300">−</button>
                                            <span class="px-3 qty-text">{{ $item['qty'] }}</span>
                                            <button type="button"
                                                class="px-3 cursor-pointer btn-plus rounded bg-gray-200 hover:bg-gray-300">+</button>
                                            <input type="hidden" name="qty" value="{{ $item['qty'] }}">
                                        </form>
                                    </td>
                                    <td class="px-6 py-3 text-center">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    <td class="px-6 py-3 text-center flex justify-center gap-2">
                                        <form id="remove-{{ $id }}"
                                            action="{{ route('kasir.transaksi.remove', $id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-semibold shadow transition flex items-center gap-1">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Ringkasan --}}
                <div class="p-6 border-t border-blue-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-lg font-semibold text-gray-800">
                        Total: Rp {{ number_format($total, 0, ',', '.') }}
                    </div>
                    <form id="checkout-form" action="{{ route('kasir.transaksi.checkout') }}" method="POST"
                        class="flex flex-col md:flex-row gap-3 items-center w-full md:w-auto">
                        @csrf
                        <input type="text" name="diskon" placeholder="Diskon"
                            class="border px-3 py-2 rounded focus:ring focus:ring-indigo-500">
                        <input type="text" name="dibayar" placeholder="Dibayar"
                            class="border px-3 py-2 rounded focus:ring focus:ring-indigo-500">
                        <select name="metode_bayar" class="border px-3 py-2 rounded focus:ring focus:ring-indigo-500">
                            <option value="cash">Cash</option>
                            <option value="debit">Cashless</option>
                        </select>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md font-semibold">
                            Bayar Sekarang
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <script>
        const totalHarga = {{ $total ?? 0 }};
        const diskonInput = document.getElementById('diskon');
        const dibayarInput = document.getElementById('dibayar');
        const dibayarWrapper = document.getElementById('dibayar-wrapper');
        const diskonText = document.getElementById('diskon-text');
        const subtotalText = document.getElementById('subtotal-text');
        const checkoutForm = document.getElementById('checkout-form');
        const metodeSelect = document.getElementById('metode_bayar');
        const totalInput = document.getElementById('total-input');
        const btnBayar = document.getElementById('btn-bayar');
        const btnBatal = document.getElementById('btn-batal');

        // Variable untuk menyimpan order_id dari Midtrans
        let currentOrderId = null;

        // Format Rupiah
        function formatRupiah(angka) {
            angka = angka.toString().replace(/[^0-9]/g, "");
            return angka ? "Rp " + angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".") : "Rp 0";
        }

        function getAngka(str) {
            return parseInt(str.replace(/[^0-9]/g, "")) || 0;
        }

        function updateSubtotal() {
            const diskon = getAngka(diskonInput.value);
            const subtotal = Math.max(totalHarga - diskon, 0);
            diskonText.textContent = "-" + formatRupiah(diskon);
            subtotalText.textContent = formatRupiah(subtotal);
        }

        // Toggle input "dibayar" kalau pilih Debit
        function toggleDibayar() {
            if (metodeSelect.value === 'debit') {
                dibayarWrapper.style.display = 'none';
                dibayarInput.removeAttribute('required');
            } else {
                dibayarWrapper.style.display = 'block';
                dibayarInput.setAttribute('required', 'true');
            }
        }

        metodeSelect.addEventListener('change', toggleDibayar);
        toggleDibayar();

        [diskonInput, dibayarInput].forEach(input => {
            input.addEventListener('input', function() {
                let angka = getAngka(this.value);
                this.value = formatRupiah(angka);
                if (this.id === 'diskon') updateSubtotal();
            });
        });

        updateSubtotal();

        // ✅ Tombol tambahan (awal disembunyikan)
        const tombolCetak = document.createElement("button");
        tombolCetak.textContent = "Cetak Struk";
        tombolCetak.id = "btnCetak";
        tombolCetak.type = "button";
        tombolCetak.className = "hidden w-full bg-green-600 text-white px-4 py-2 rounded mt-3 hover:bg-green-700";
        checkoutForm.appendChild(tombolCetak);

        tombolCetak.addEventListener("click", function() {
            window.location.href = "{{ route('kasir') }}";
        });

        // ✅ Tombol "Batalkan Pembayaran" - DIPERBAIKI
        btnBatal.addEventListener("click", async function(e) {
            e.preventDefault();

            // Cek apakah ada transaksi Midtrans yang sedang berjalan
            if (!currentOrderId) {
                Swal.fire({
                    title: "Tidak Ada Transaksi",
                    text: "Tidak ada transaksi yang perlu dibatalkan.",
                    icon: "info",
                    confirmButtonColor: "#3b82f6",
                });
                return;
            }

            const result = await Swal.fire({
                title: "Batalkan Pembayaran?",
                html: `
                    <p>Pilih cara pembatalan transaksi:</p>
                    <div class="mt-4 space-y-2">
                        <button id="btn-cancel-midtrans" class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            Batalkan di Midtrans
                        </button>
                        <button id="btn-cancel-local" class="w-full bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">
                            Batalkan Lokal Saja
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">Order ID: ${currentOrderId}</p>
                `,
                icon: "warning",
                showCancelButton: true,
                showConfirmButton: false,
                cancelButtonText: "Batal",
                cancelButtonColor: "#6b7280",
                didOpen: () => {
                    // Handler untuk tombol batalkan di Midtrans
                    document.getElementById('btn-cancel-midtrans').addEventListener('click',
                        async () => {
                            Swal.close();
                            await cancelViaMidtrans();
                        });

                    // Handler untuk tombol batalkan lokal
                    document.getElementById('btn-cancel-local').addEventListener('click', () => {
                        Swal.close();
                        cancelLocal();
                    });
                }
            });
        });

        // Function untuk cancel lokal (tanpa API Midtrans)
        function cancelLocal() {
            Swal.fire({
                title: "Transaksi Dibatalkan",
                html: `
                    <p>Transaksi lokal telah dibatalkan.</p>
                    <p class="text-sm text-gray-600 mt-2">Transaksi di Midtrans akan expired otomatis dalam 24 jam.</p>
                `,
                icon: "info",
                confirmButtonColor: "#3b82f6",
            }).then(() => {
                currentOrderId = null;
                location.reload();
            });
        }

        // ✅ Submit pembayaran - DIPERBAIKI
        btnBayar.addEventListener('click', async function(e) {
            e.preventDefault();
            const metode = metodeSelect.value;
            const total = totalHarga;
            const diskon = getAngka(diskonInput.value);
            const subtotal = Math.max(total - diskon, 0);
            const dibayar = getAngka(dibayarInput.value);

            // Validasi Cash manual
            if (metode !== 'debit' && dibayar < subtotal) {
                Swal.fire({
                    title: "Nominal Kurang!",
                    text: "Nominal dibayar kurang dari subtotal!",
                    icon: "warning",
                    confirmButtonColor: "#ef4444",
                });
                return;
            }

            // 🔹 Kalau Cash manual tetap sama
            checkoutForm.submit();
        });
    </script>
    <script>
        document.querySelectorAll(".update-cart-form").forEach(form => {
            const stok = parseInt(form.dataset.stok);
            const qtySpan = form.querySelector(".qty-text");
            const btnPlus = form.querySelector(".btn-plus");
            const btnMinus = form.querySelector(".btn-minus");

            let qty = parseInt(qtySpan.innerText);

            // ✅ Buat hidden input untuk qty
            let qtyInput = form.querySelector('input[name="qty"]');
            if (!qtyInput) {
                qtyInput = document.createElement('input');
                qtyInput.type = 'hidden';
                qtyInput.name = 'qty';
                form.appendChild(qtyInput);
            }

            function updateDisplay() {
                qtySpan.innerText = qty;
                qtyInput.value = qty; // ✅ Update value hidden input

                // ✅ Disable tombol sesuai kondisi
                if (qty >= stok) {
                    btnPlus.disabled = true;
                    btnPlus.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    btnPlus.disabled = false;
                    btnPlus.classList.remove('opacity-50', 'cursor-not-allowed');
                }

                if (qty <= 1) {
                    btnMinus.disabled = true;
                    btnMinus.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    btnMinus.disabled = false;
                    btnMinus.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            btnPlus.addEventListener("click", function() {
                if (qty < stok) {
                    qty++;
                    updateDisplay();
                    form.submit(); // submit otomatis ke backend
                } else {
                    // ✅ Alert SweetAlert ketika stok habis
                    Swal.fire({
                        icon: "error",
                        title: "Stok Tidak Mencukupi!",
                        html: `
                        <p>Jumlah yang Anda pilih sudah mencapai batas stok.</p>
                        <p class="text-sm text-gray-600 mt-2">Stok tersedia: <strong>${stok}</strong></p>
                    `,
                        confirmButtonColor: "#ef4444",
                        confirmButtonText: "OK"
                    });
                }
            });

            btnMinus.addEventListener("click", function() {
                if (qty > 1) {
                    qty--;
                    updateDisplay();
                    form.submit(); // ✅ Submit dengan qty yang sudah dikurangi
                } else {
                    // ✅ Alert jika ingin mengurangi qty = 1
                    Swal.fire({
                        icon: "warning",
                        title: "Hapus Item?",
                        text: "Jumlah minimal adalah 1. Apakah Anda ingin menghapus item ini dari keranjang?",
                        showCancelButton: true,
                        confirmButtonColor: "#ef4444",
                        cancelButtonColor: "#6b7280",
                        confirmButtonText: "Ya, Hapus",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit form hapus
                            const formId = form.closest('.flex.items-start').querySelector(
                                    'button[onclick*="remove-"]')
                                .getAttribute('onclick').match(/remove-(\d+)/)[1];
                            document.getElementById('remove-' + formId).submit();
                        }
                    });
                }
            });

            updateDisplay(); // awal
        });
    </script>
@endsection
