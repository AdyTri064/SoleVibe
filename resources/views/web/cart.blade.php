<x-layout>
    <x-slot name="title">Keranjang Belanja</x-slot>

    @if(session('success'))
        <div class="container mt-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="container my-5">
        <h1 class="mb-4">Keranjang Belanja</h1>

        @if($cart && count($cart->items))
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-3">
                        <div class="card-body p-3">
                            @forelse($cart->items as $item)
                                <div class="cart-item d-flex align-items-center mb-3 border-bottom pb-3">
                                    @if($item->itemable)
                                        <img src="{{ $item->itemable->image_url ? Storage::url($item->itemable->image_url) : 'https://via.placeholder.com/80?text=Product' }}"
                                             class="cart-img me-3 rounded"
                                             alt="{{ $item->itemable->name }}">

                                        <div class="flex-grow-1">
                                            <h5 class="cart-item-name mb-1">{{ $item->itemable->name }}</h5>
                                            <p class="cart-item-price mb-0 text-dark">Rp.{{ number_format($item->itemable->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline-flex me-2">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" name="action" value="decrease" class="btn btn-outline-dark btn-sm" {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                                <input type="text" name="quantity" value="{{ $item->quantity }}" class="form-control form-control-sm text-center mx-1" style="width: 50px;" readonly>
                                                <button type="submit" name="action" value="increase" class="btn btn-outline-dark btn-sm">+</button>
                                            </form>

                                            <span class="cart-item-subtotal mb-0 me-3 fw-semibold text-dark">Rp.{{ number_format($item->itemable->price * $item->quantity, 0, ',', '.') }}</span>

                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-dark btn-sm text-white" title="Hapus">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <img src="https://via.placeholder.com/80?text=Error" class="cart-img me-3 rounded" alt="Produk Tidak Ditemukan">
                                        <div class="flex-grow-1">
                                            <h5 class="cart-item-name mb-1 text-danger">Produk Tidak Ditemukan</h5>
                                            <p class="cart-item-price mb-0 text-muted">Item ini mungkin sudah tidak tersedia.</p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item yang tidak valid ini dari keranjang?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-dark btn-sm text-white" title="Hapus item yang tidak valid">
                                                    <i class="bi bi-trash"></i> Hapus Invalid
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="alert alert-info">
                                    Keranjang belanja Anda kosong.
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <a href="{{ URL::to('/products') }}" class="btn btn-dark text-white mt-2">
                        <i class="bi bi-arrow-left"></i> Lanjut Belanja
                    </a>
                </div>
                <div class="col-lg-4">
                    <div class="card p-3">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Ringkasan Pesanan</h5>
                            <div class="d-flex justify-content-between total-section mb-2">
                                <span>Subtotal</span>
                                <span class="text-dark">Rp.{{ number_format($cart->calculatedPriceByQuantity(), 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between total-section fw-bold">
                                <span>Total</span>
                                <span class="text-dark">Rp.{{ number_format($cart->calculatedPriceByQuantity(), 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('checkout.index') }}" class="btn btn-dark text-white w-100 mt-3">Lanjut ke Pembayaran</a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info">
                Keranjang belanja Anda kosong.
            </div>
        @endif
    </div>

    @push('styles')
    <style>
        .btn-dark {
            background-color: #000;
            border: none;
        }
        .btn-dark:hover {
            background-color: #333;
        }
        .text-dark {
            color: #000 !important;
        }
    </style>
    @endpush
</x-layout>
