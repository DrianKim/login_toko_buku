@extends('kasir.layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-book text-indigo-600"></i> Data Buku
            </h1>
        </div>

        <!-- Filter & Search -->
        <div class="flex flex-wrap items-center justify-between mb-6 gap-3">
            <form action="{{ route('kasir.buku') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul / kode..."
                    class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <select name="kategori_id"
                    class="cursor-pointer px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategori as $group => $items)
                        <optgroup label="{{ $group }}">
                            @foreach ($items as $kat)
                                <option value="{{ $kat->id }}"
                                    {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->jenis }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>

                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md cursor-pointer">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Grid Buku -->
        <div id="bookGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @php $cart = session('keranjang', []); @endphp
            @foreach ($buku as $item)
                @php
                    $inCart = isset($cart[$item->id]);
                    $stok = $item->Tbdetail->stok ?? 0;
                    $harga = $item->Tbdetail->harga ?? 0;
                    $qtyInCart = $inCart ? $cart[$item->id]['qty'] : 0;
                @endphp

                <div class="book-card bg-white rounded-xl shadow hover:shadow-lg transition flex flex-col p-4"
                    data-id="{{ $item->id }}" data-stock-original="{{ $stok }}">
                    <div class="relative w-full mb-4 pt-[150%] rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $item->cover_buku) }}"
                            class="absolute inset-0 w-full h-full object-cover">
                    </div>

                    <h3 class="font-semibold text-lg text-gray-800 mb-1 line-clamp-2">{{ $item->judul_buku }}</h3>
                    <p class="text-sm text-gray-600 mb-1 flex items-center gap-1">
                        <i class="fas fa-tag text-blue-500"></i> {{ $item->Tbkategori->kategori ?? '-' }}
                    </p>
                    <p class="text-sm text-gray-600 mb-1 flex items-center gap-1">
                        <i class="fas fa-cubes text-purple-500"></i> Stok:
                        <span class="stok-value font-medium">{{ $stok - $qtyInCart }}</span>
                    </p>
                    <p class="text-sm text-gray-600 mb-3 flex items-center gap-1">
                        <i class="fas fa-dollar-sign text-yellow-500"></i> Rp {{ number_format($harga, 0, ',', '.') }}
                    </p>

                    <div class="actions mt-auto flex gap-2">
                        @include('kasir.modal.show_buku', ['buku' => $buku])
                        <button onclick="showDetail({{ $item->id }})"
                            class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-700 py-2 rounded-lg shadow transition flex items-center justify-center gap-2 border border-blue-400 font-semibold">
                            <i class="fas fa-eye"></i> Lihat
                        </button>

                        @if ($item->Tbdetail->stok > 0)
                            @if ($inCart)
                                <div class="cart-controls flex-1 flex items-center justify-center gap-2">
                                    <button type="button"
                                        class="btn-minus w-1/3 bg-blue-100 hover:bg-blue-200 text-blue-700 py-2 rounded-lg border border-blue-400 font-semibold"
                                        data-id="{{ $item->id }}">-</button>
                                    <div
                                        class="qty-display w-1/3 text-center bg-white border border-blue-200 rounded-lg py-2 font-semibold text-blue-700">
                                        {{ $qtyInCart }}</div>
                                    <button type="button"
                                        class="btn-plus w-1/3 bg-blue-100 hover:bg-blue-200 text-blue-700 py-2 rounded-lg border border-blue-400 font-semibold"
                                        data-id="{{ $item->id }}">+</button>
                                </div>
                            @else
                                <button type="button"
                                    class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-700 py-2 rounded-lg border border-blue-400 font-semibold add-to-cart"
                                    data-id="{{ $item->id }}">
                                    <i class="fas fa-cart-plus"></i> Keranjang
                                </button>
                            @endif
                        @endif
                        {{-- Kalau stok 0, ga ada tombol sama sekali --}}
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Floating Checkout Button -->
        <div id="checkoutButton" class="{{ count($cart) > 0 ? '' : 'hidden' }} fixed bottom-6 right-6 z-50">
            <button id="openCheckoutBtn"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2">
                <i class="fas fa-shopping-cart"></i>
                <span id="checkoutInfo">Checkout ({{ count($cart) }})</span>
            </button>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div id="checkoutModal" class="fixed inset-0 hidden items-center justify-center z-50 bg-black/40">
        <div class="bg-white rounded-xl w-11/12 md:w-2/3 p-6 max-h-[80vh] overflow-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Checkout</h3>
                <button onclick="closeCheckout()" class="text-gray-600 hover:text-black">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div id="checkoutItems" class="space-y-2 mb-4"></div>

            <div class="flex gap-2 mb-4">
                <div class="flex-1">
                    <label class="block text-sm">Diskon</label>
                    <input type="number" id="diskonInput" value="0" class="w-full p-2 border rounded-md">
                </div>
                <div class="flex-1">
                    <label class="block text-sm">Metode Bayar</label>
                    <select id="metodeBayar" class="w-full p-2 border rounded-md">
                        <option value="cash">Cash</option>
                        <option value="cashless">Cashless</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm">Dibayar (jika cash)</label>
                <input type="number" id="dibayarInput" placeholder="Masukkan jumlah dibayar"
                    class="w-full p-2 border rounded-md">
            </div>

            <div class="text-right">
                <p class="font-semibold">Subtotal: <span id="modalSubtotal">Rp 0</span></p>
                <p class="font-semibold">Total (setelah diskon): <span id="modalTotal">Rp 0</span></p>
                <p class="font-semibold">Kembalian: <span id="modalKembali">Rp 0</span></p>
            </div>

            <div class="flex gap-2 mt-4">
                <button type="button" onclick="closeCheckout()" class="px-4 py-2 rounded-lg border">Tambah Buku</button>
                <form id="checkoutForm" method="POST" action="{{ route('kasir.checkout') }}">
                    @csrf
                    <input type="hidden" name="diskon" id="formDiskon" value="0">
                    <input type="hidden" name="metode_bayar" id="formMetodeBayar" value="cash">
                    <input type="hidden" name="dibayar" id="formDibayar" value="0">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-green-600 text-white">Selesaikan
                        Checkout</button>
                </form>
                <button type="button" onclick="clearCartAndClose()"
                    class="px-4 py-2 rounded-lg border text-red-600">Batal</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';

        // === Realtime Refresh ===
        async function refreshFromServer() {
            const res = await axios.get('{{ route('kasir.keranjang.get') }}');
            const data = res.data.keranjang || {};
            const count = res.data.count || 0;
            const checkoutBtn = document.getElementById('openCheckoutBtn');
            const checkoutInfo = document.getElementById('checkoutInfo');

            if (count > 0) {
                checkoutBtn.classList.remove('hidden');
                checkoutInfo.textContent = `Checkout (${count})`;
            } else checkoutBtn.classList.add('hidden');

            document.querySelectorAll('.book-card').forEach(card => {
                const id = card.dataset.id;
                const stokAwal = parseInt(card.dataset.stockOriginal);
                const stokSpan = card.querySelector('.stok-value');
                const actions = card.querySelector('.actions');
                const inCart = data[id];
                const qty = inCart ? inCart.qty : 0;
                const sisa = stokAwal - qty;
                stokSpan.textContent = sisa;

                let controls = actions.querySelector('.cart-controls');
                let addBtn = actions.querySelector('.add-to-cart');

                if (qty > 0) {
                    if (!controls) {
                        if (addBtn) addBtn.remove();
                        controls = document.createElement('div');
                        controls.className = 'cart-controls flex-1 flex items-center justify-center gap-2';
                        controls.innerHTML =
                            `
                    <button class="btn-minus w-1/3 bg-blue-100 hover:bg-blue-200 text-blue-700 py-2 rounded-lg border border-blue-400 font-semibold" data-id="${id}">-</button>
                    <div class="qty-display w-1/3 text-center bg-white border border-blue-200 rounded-lg py-2 font-semibold text-blue-700">${qty}</div>
                    <button class="btn-plus w-1/3 bg-blue-100 hover:bg-blue-200 text-blue-700 py-2 rounded-lg border border-blue-400 font-semibold" data-id="${id}">+</button>`;
                        actions.appendChild(controls);
                    } else controls.querySelector('.qty-display').textContent = qty;
                } else {
                    if (controls) controls.remove();
                    if (!addBtn) {
                        addBtn = document.createElement('button');
                        addBtn.className =
                            'flex-1 bg-blue-100 hover:bg-blue-200 text-blue-700 py-2 rounded-lg border border-blue-400 font-semibold add-to-cart';
                        addBtn.dataset.id = id;
                        addBtn.innerHTML = `<i class="fas fa-cart-plus"></i> Keranjang`;
                        actions.appendChild(addBtn);
                    }
                }
            });
        }

        // === Event Click ===
        document.addEventListener('click', async e => {
            const add = e.target.closest('.add-to-cart');
            const plus = e.target.closest('.btn-plus');
            const minus = e.target.closest('.btn-minus');

            if (add) {
                await axios.post('{{ route('kasir.keranjang.tambah') }}', {
                    buku_id: add.dataset.id
                });
                await refreshFromServer();
            }
            if (plus) {
                const newQty = parseInt(plus.parentElement.querySelector('.qty-display').textContent) + 1;
                await axios.post('{{ route('kasir.keranjang.update') }}', {
                    id: plus.dataset.id,
                    qty: newQty
                });
                await refreshFromServer();
            }
            if (minus) {
                const newQty = parseInt(minus.parentElement.querySelector('.qty-display').textContent) - 1;
                if (newQty <= 0)
                    await axios.post('{{ route('kasir.keranjang.hapus') }}', {
                        id: minus.dataset.id
                    });
                else
                    await axios.post('{{ route('kasir.keranjang.update') }}', {
                        id: minus.dataset.id,
                        qty: newQty
                    });
                await refreshFromServer();
            }
        });

        // === Modal Checkout ===
        document.getElementById('openCheckoutBtn')?.addEventListener('click', async () => {
            const res = await axios.get('{{ route('kasir.keranjang.get') }}');
            const cart = res.data.keranjang;
            const container = document.getElementById('checkoutItems');
            const modalSubtotal = document.getElementById('modalSubtotal');
            const modalTotal = document.getElementById('modalTotal');
            const modalKembali = document.getElementById('modalKembali');
            let subtotal = 0;

            container.innerHTML = '';
            for (const id in cart) {
                const item = cart[id];
                subtotal += item.subtotal;
                container.innerHTML += `
        <div class="flex justify-between border-b pb-2">
            <span>${item.judul_buku} (x${item.qty})</span>
            <span>Rp ${item.subtotal.toLocaleString()}</span>
        </div>`;
            }

            modalSubtotal.textContent = `Rp ${subtotal.toLocaleString()}`;
            modalTotal.textContent = `Rp ${subtotal.toLocaleString()}`;
            modalKembali.textContent = `Rp 0`;

            document.getElementById('diskonInput').value = 0;
            document.getElementById('dibayarInput').value = '';
            document.getElementById('checkoutModal').classList.remove('hidden');
            document.getElementById('checkoutModal').classList.add('flex');

            const updateTotal = () => {
                const diskon = parseInt(document.getElementById('diskonInput').value) || 0;
                const dibayar = parseInt(document.getElementById('dibayarInput').value) || 0;
                const total = Math.max(0, subtotal - diskon);
                const kembali = dibayar > total ? dibayar - total : 0;
                modalTotal.textContent = `Rp ${total.toLocaleString()}`;
                modalKembali.textContent = `Rp ${kembali.toLocaleString()}`;
                document.getElementById('formDiskon').value = diskon;
                document.getElementById('formMetodeBayar').value = document.getElementById('metodeBayar')
                    .value;
                document.getElementById('formDibayar').value = dibayar;
            };
            document.getElementById('diskonInput').oninput = updateTotal;
            document.getElementById('dibayarInput').oninput = updateTotal;
            document.getElementById('metodeBayar').onchange = updateTotal;
        });

        function closeCheckout() {
            document.getElementById('checkoutModal').classList.add('hidden');
            document.getElementById('checkoutModal').classList.remove('flex');
        }

        async function clearCartAndClose() {
            await refreshFromServer();
            closeCheckout();
        }

        // === Checkout Submit (langsung redirect ke struk) ===
        document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);

            try {
                const res = await axios.post(form.action, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (res.data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Checkout Berhasil!',
                        text: 'Mengalihkan ke struk...',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    setTimeout(() => {
                        window.location.href = res.data.redirect;
                    }, 1500);
                } else {
                    Swal.fire('Gagal', res.data.message || 'Terjadi kesalahan', 'error');
                }
            } catch (err) {
                console.error(err.response?.data || err);
                Swal.fire('Error', 'Gagal melakukan checkout', 'error');
            }
        });

        (async () => await refreshFromServer())();

        // Modal Detail Buku
        function showDetail(id) {
            const modal = document.getElementById('showBukuModal' + id);
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        // Close modal
        document.querySelectorAll('[data-modal-close]').forEach(btn => {
            btn.addEventListener('click', function() {
                const target = this.getAttribute('data-modal-close');
                const modal = document.getElementById(target);
                if (modal) modal.classList.add('hidden');
            });
        });
    </script>
@endsection
