@extends('layouts.app')
@section('content')
<h3>Checkout</h3>
<form method="get" action="{{ route('checkout.show') }}" class="row g-2 mb-3">
    <div class="col-md-9"><input name="promo_code" class="form-control" value="{{ $promoCode ?? '' }}" placeholder="Masukkan kode promo"></div>
    <div class="col-md-3"><button class="btn btn-outline-dark w-100">Cek Promo</button></div>
</form>
@if(isset($promotion) && $promotion)
    <div class="alert alert-info">Promo aktif: <b>{{ $promotion->code }}</b>, potongan Rp {{ number_format($discount, 0, ',', '.') }}</div>
@endif

<form method="post" action="{{ route('checkout.process') }}" class="row g-3">
    @csrf
    <input type="hidden" name="promo_code" value="{{ $promotion->code ?? '' }}">
    <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name') }}">
    <input type="hidden" name="city_name" id="city_name" value="{{ old('city_name') }}">
    <input type="hidden" name="district_name" id="district_name" value="{{ old('district_name') }}">
    <div class="col-12">
        <label>Alamat Pengiriman</label>
        <textarea name="address" class="form-control" required>{{ old('address', auth()->user()->default_address) }}</textarea>
    </div>
    <div class="col-md-4">
        <label>Provinsi</label>
        <select name="province_id" id="province_id" class="form-select" required>
            <option value="">Pilih provinsi</option>
        </select>
    </div>
    <div class="col-md-4">
        <label>Kabupaten/Kota</label>
        <select name="city_id" id="city_id" class="form-select" required disabled>
            <option value="">Pilih kabupaten/kota</option>
        </select>
    </div>
    <div class="col-md-4">
        <label>Kecamatan</label>
        <select name="district_id" id="district_id" class="form-select" required disabled>
            <option value="">Pilih kecamatan</option>
        </select>
    </div>
    <div class="col-md-4">
        <label>Kurir</label>
        <select name="courier" id="courier" class="form-select" required>
            @foreach(($availableCouriers ?? []) as $courier)
                <option value="{{ $courier }}" @selected(old('courier', $defaultCourier ?? 'jne') === $courier)>{{ strtoupper($courier) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label>Service</label>
        <select name="service" id="service" class="form-select" required disabled>
            <option value="">Pilih service</option>
        </select>
    </div>
    <div class="col-md-4">
        <label>Biaya Kirim</label>
        <input name="shipping_cost" id="shipping_cost" type="number" min="0" class="form-control" placeholder="Biaya Kirim" value="{{ old('shipping_cost', 0) }}" required readonly>
    </div>
    <div class="col-12">
        <small id="shipping_info" class="text-muted">Pilih kabupaten/kota dan kurir untuk melihat opsi ongkir.</small>
    </div>
    <div class="col-12"><textarea name="note" class="form-control" placeholder="Catatan">{{ old('note') }}</textarea></div>
    <div class="col-12">
        <h6>Subtotal: Rp {{ number_format($cart->items->sum('subtotal'), 0, ',', '.') }}</h6>
        <h6>Diskon: Rp {{ number_format($discount ?? 0, 0, ',', '.') }}</h6>
        <h6>Ongkir: Rp <span id="shipping_cost_text">{{ number_format((int) old('shipping_cost', 0), 0, ',', '.') }}</span></h6>
        <h5>Total: Rp <span id="grand_total_text">{{ number_format(($cart->items->sum('subtotal') - ($discount ?? 0) + (int) old('shipping_cost', 0)), 0, ',', '.') }}</span></h5>
        <button class="btn btn-dark">Buat Pesanan</button>
    </div>
</form>

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
    const oldShippingCost = Number(@json(old('shipping_cost', 0)));

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
            if (selectedValue && String(selectedValue) === String(item.id)) {
                option.selected = true;
            }
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

    const formatRupiah = (value) => {
        const number = Number(value || 0);
        return number.toLocaleString('id-ID');
    };

    const updateTotals = () => {
        const shipping = Number(shippingCostInput.value || 0);
        shippingCostText.textContent = formatRupiah(shipping);
        grandTotalText.textContent = formatRupiah(subtotalValue - discountValue + shipping);
    };

    const setShippingOptions = (options, selectedService = null) => {
        resetSelect(serviceSelect, 'Pilih service');
        serviceSelect.disabled = true;
        shippingCostInput.value = 0;

        if (!options || options.length === 0) {
            updateTotals();
            return;
        }

        options.forEach((option) => {
            const opt = document.createElement('option');
            opt.value = option.service;
            opt.textContent = `${option.service} - ${option.description} (Rp ${formatRupiah(option.cost)} | ETD ${option.etd || '-'})`;
            opt.dataset.cost = option.cost;
            if (selectedService && selectedService === option.service) {
                opt.selected = true;
            }
            serviceSelect.appendChild(opt);
        });

        serviceSelect.disabled = false;
        const selected = serviceSelect.selectedOptions[0];
        shippingCostInput.value = selected ? Number(selected.dataset.cost || 0) : 0;
        updateTotals();
    };

    const provinces = await fetchJson('{{ route('checkout.locations.provinces') }}');
    fillSelect(provinceSelect, provinces, oldProvince);
    syncHiddenText(provinceSelect, provinceName);

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
        const selected = serviceSelect.selectedOptions[0];
        shippingInfo.textContent = selected ? `Ongkir terpilih: ${selected.textContent}` : 'Pilih service pengiriman.';
    };

    provinceSelect.addEventListener('change', async function () {
        syncHiddenText(provinceSelect, provinceName);
        await loadCities(provinceSelect.value);
        await loadShippingOptions('', courierSelect.value);
    });

    citySelect.addEventListener('change', async function () {
        syncHiddenText(citySelect, cityName);
        await loadDistricts(citySelect.value);
        await loadShippingOptions(citySelect.value, courierSelect.value);
    });

    districtSelect.addEventListener('change', function () {
        syncHiddenText(districtSelect, districtName);
    });

    courierSelect.addEventListener('change', async function () {
        await loadShippingOptions(citySelect.value, courierSelect.value);
    });

    serviceSelect.addEventListener('change', function () {
        const selected = serviceSelect.selectedOptions[0];
        shippingCostInput.value = selected ? Number(selected.dataset.cost || 0) : 0;
        shippingInfo.textContent = selected ? `Ongkir terpilih: ${selected.textContent}` : 'Pilih service pengiriman.';
        updateTotals();
    });

    if (oldProvince) {
        await loadCities(oldProvince, oldCity);
    }

    if (oldCity) {
        await loadDistricts(oldCity, oldDistrict);
        await loadShippingOptions(oldCity, oldCourier, oldService);
    } else {
        updateTotals();
    }
});
</script>
@endsection
