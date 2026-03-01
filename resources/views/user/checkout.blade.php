@extends('layouts.app')
@section('content')
<h3 class="section-title">Checkout</h3>

<div class="card-pro mb-3">
    <div class="card-body">
        <form method="get" action="{{ route('checkout.show') }}" class="row g-2 align-items-end">
            <div class="col-md-9">
                <label class="form-label">Kode Promo</label>
                <input name="promo_code" class="form-control" value="{{ $promoCode ?? '' }}" placeholder="Masukkan kode promo">
            </div>
            <div class="col-md-3 d-grid"><button class="btn btn-outline-dark"><i class="bi bi-ticket-perforated me-1"></i>Cek Promo</button></div>
        </form>
        @if(isset($promotion) && $promotion)
            <div class="alert alert-info mt-3 mb-0 rounded-4 border-0">Promo <strong>{{ $promotion->code }}</strong> aktif. Potongan Rp {{ number_format($discount, 0, ',', '.') }}.</div>
        @endif
    </div>
</div>

<div class="card-pro">
    <div class="card-body">
        <form method="post" action="{{ route('checkout.process') }}" class="row g-3">
            @csrf
            <input type="hidden" name="promo_code" value="{{ $promotion->code ?? '' }}">
            <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name') }}">
            <input type="hidden" name="city_name" id="city_name" value="{{ old('city_name') }}">
            <input type="hidden" name="district_name" id="district_name" value="{{ old('district_name') }}">

            <div class="col-12">
                <label class="form-label">Alamat Pengiriman</label>
                <textarea name="address" class="form-control" required>{{ old('address', auth()->user()->default_address) }}</textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Provinsi</label>
                <select name="province_id" id="province_id" class="form-select" required><option value="">Pilih provinsi</option></select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Kabupaten/Kota</label>
                <select name="city_id" id="city_id" class="form-select" required disabled><option value="">Pilih kabupaten/kota</option></select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Kecamatan</label>
                <select name="district_id" id="district_id" class="form-select" required disabled><option value="">Pilih kecamatan</option></select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Kurir</label>
                <select name="courier" id="courier" class="form-select" required>
                    @foreach(($availableCouriers ?? []) as $courier)
                        <option value="{{ $courier }}" @selected(old('courier', $defaultCourier ?? 'jne') === $courier)>{{ strtoupper($courier) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Service</label>
                <select name="service" id="service" class="form-select" required disabled><option value="">Pilih service</option></select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Biaya Kirim</label>
                <input name="shipping_cost" id="shipping_cost" type="number" min="0" class="form-control" value="{{ old('shipping_cost', 0) }}" required readonly>
            </div>
            <div class="col-12"><small id="shipping_info" class="muted">Pilih kabupaten/kota dan kurir untuk melihat opsi ongkir.</small></div>

            <div class="col-12">
                <label class="form-label">Catatan (Opsional)</label>
                <textarea name="note" class="form-control" style="min-height:80px">{{ old('note') }}</textarea>
            </div>

            <div class="col-12">
                <div class="row g-2">
                    <div class="col-md-3"><div class="stat-card">Subtotal<br><strong>Rp {{ number_format($cart->items->sum('subtotal'), 0, ',', '.') }}</strong></div></div>
                    <div class="col-md-3"><div class="stat-card">Diskon<br><strong>Rp {{ number_format($discount ?? 0, 0, ',', '.') }}</strong></div></div>
                    <div class="col-md-3"><div class="stat-card">Ongkir<br><strong>Rp <span id="shipping_cost_text">{{ number_format((int) old('shipping_cost', 0), 0, ',', '.') }}</span></strong></div></div>
                    <div class="col-md-3"><div class="stat-card">Total<br><strong>Rp <span id="grand_total_text">{{ number_format(($cart->items->sum('subtotal') - ($discount ?? 0) + (int) old('shipping_cost', 0)), 0, ',', '.') }}</span></strong></div></div>
                </div>
            </div>

            <div class="col-12 d-grid d-md-flex justify-content-md-end">
                <button class="btn btn-primary btn-pill px-4"><i class="bi bi-bag-check me-1"></i>Buat Pesanan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async function () {
    const provinceSelect = document.getElementById('province_id');
    const citySelect = document.getElementById('city_id');
    const districtSelect = document.getElementById('district_id');
    const provinceName = document.getElementById('province_name');
    const cityName = document.getElementById('city_name');
    const districtName = document.getElementById('district_name');
    const courierSelect = document.getElementById('courier');
    const serviceSelect = document.getElementById('service');
    const shippingCostInput = document.getElementById('shipping_cost');
    const shippingInfo = document.getElementById('shipping_info');
    const shippingCostText = document.getElementById('shipping_cost_text');
    const grandTotalText = document.getElementById('grand_total_text');

    const subtotalValue = {{ (int) $cart->items->sum('subtotal') }};
    const discountValue = {{ (int) ($discount ?? 0) }};

    const oldProvince = @json(old('province_id'));
    const oldCity = @json(old('city_id'));
    const oldDistrict = @json(old('district_id'));
    const oldCourier = @json(old('courier', $defaultCourier ?? 'jne'));
    const oldService = @json(old('service'));

    const resetSelect = (el, placeholder) => {
        el.innerHTML = '';
        const option = document.createElement('option');
        option.value = '';
        option.textContent = placeholder;
        el.appendChild(option);
    };

    const fillSelect = (el, items, selectedValue) => {
        items.forEach((item) => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            if (selectedValue && String(selectedValue) === String(item.id)) option.selected = true;
            el.appendChild(option);
        });
    };

    const fetchJson = async (url) => {
        try {
            const res = await fetch(url);
            const data = await res.json();
            return data.data ?? [];
        } catch (e) {
            return [];
        }
    };

    const syncHiddenText = (selectEl, hiddenEl) => {
        hiddenEl.value = selectEl.selectedOptions.length ? selectEl.selectedOptions[0].text : '';
    };

    const formatRupiah = (value) => Number(value || 0).toLocaleString('id-ID');

    const updateTotals = () => {
        const shipping = Number(shippingCostInput.value || 0);
        shippingCostText.textContent = formatRupiah(shipping);
        grandTotalText.textContent = formatRupiah(subtotalValue - discountValue + shipping);
    };

    const setShippingOptions = (options, selectedService = null) => {
        resetSelect(serviceSelect, 'Pilih service');
        serviceSelect.disabled = true;
        shippingCostInput.value = 0;

        if (!options.length) { updateTotals(); return; }

        options.forEach((option) => {
            const opt = document.createElement('option');
            opt.value = option.service;
            opt.textContent = `${option.service} - ${option.description} (Rp ${formatRupiah(option.cost)} | ETD ${option.etd || '-'})`;
            opt.dataset.cost = option.cost;
            if (selectedService && selectedService === option.service) opt.selected = true;
            serviceSelect.appendChild(opt);
        });

        serviceSelect.disabled = false;
        const selected = serviceSelect.selectedOptions[0];
        shippingCostInput.value = selected ? Number(selected.dataset.cost || 0) : 0;
        updateTotals();
    };

    const loadCities = async (provinceId, selectedCity = null) => {
        resetSelect(citySelect, 'Pilih kabupaten/kota');
        resetSelect(districtSelect, 'Pilih kecamatan');
        citySelect.disabled = true;
        districtSelect.disabled = true;
        cityName.value = '';
        districtName.value = '';
        if (!provinceId) return;
        const cities = await fetchJson('{{ route('checkout.locations.cities') }}?province_id=' + encodeURIComponent(provinceId));
        fillSelect(citySelect, cities, selectedCity);
        citySelect.disabled = false;
        syncHiddenText(citySelect, cityName);
    };

    const loadDistricts = async (cityId, selectedDistrict = null) => {
        resetSelect(districtSelect, 'Pilih kecamatan');
        districtSelect.disabled = true;
        districtName.value = '';
        if (!cityId) return;
        const districts = await fetchJson('{{ route('checkout.locations.districts') }}?city_id=' + encodeURIComponent(cityId));
        fillSelect(districtSelect, districts, selectedDistrict);
        districtSelect.disabled = false;
        syncHiddenText(districtSelect, districtName);
    };

    const loadShippingOptions = async (cityId, courier, selectedService = null) => {
        if (!cityId || !courier) {
            setShippingOptions([]);
            shippingInfo.textContent = 'Pilih kabupaten/kota dan kurir untuk melihat opsi ongkir.';
            return;
        }
        shippingInfo.textContent = 'Menghitung ongkir...';
        const options = await fetchJson('{{ route('checkout.shipping-options') }}?city_id=' + encodeURIComponent(cityId) + '&courier=' + encodeURIComponent(courier));
        if (!options.length) {
            setShippingOptions([]);
            shippingInfo.textContent = 'Ongkir tidak tersedia untuk kombinasi lokasi/kurir ini.';
            return;
        }
        setShippingOptions(options, selectedService);
        shippingInfo.textContent = serviceSelect.selectedOptions[0] ? `Ongkir terpilih: ${serviceSelect.selectedOptions[0].textContent}` : 'Pilih service pengiriman.';
    };

    const provinces = await fetchJson('{{ route('checkout.locations.provinces') }}');
    fillSelect(provinceSelect, provinces, oldProvince);
    syncHiddenText(provinceSelect, provinceName);

    provinceSelect.addEventListener('change', async () => {
        syncHiddenText(provinceSelect, provinceName);
        await loadCities(provinceSelect.value);
        await loadShippingOptions('', courierSelect.value);
    });

    citySelect.addEventListener('change', async () => {
        syncHiddenText(citySelect, cityName);
        await loadDistricts(citySelect.value);
        await loadShippingOptions(citySelect.value, courierSelect.value);
    });

    districtSelect.addEventListener('change', () => syncHiddenText(districtSelect, districtName));
    courierSelect.addEventListener('change', async () => loadShippingOptions(citySelect.value, courierSelect.value));

    serviceSelect.addEventListener('change', () => {
        const selected = serviceSelect.selectedOptions[0];
        shippingCostInput.value = selected ? Number(selected.dataset.cost || 0) : 0;
        shippingInfo.textContent = selected ? `Ongkir terpilih: ${selected.textContent}` : 'Pilih service pengiriman.';
        updateTotals();
    });

    if (oldProvince) await loadCities(oldProvince, oldCity);
    if (oldCity) {
        await loadDistricts(oldCity, oldDistrict);
        await loadShippingOptions(oldCity, oldCourier, oldService);
    } else {
        updateTotals();
    }
});
</script>
@endsection
