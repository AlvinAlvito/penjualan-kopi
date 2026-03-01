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
    <div class="col-md-4"><input name="courier" class="form-control" placeholder="Kurir" required></div>
    <div class="col-md-4"><input name="service" class="form-control" placeholder="Service" required></div>
    <div class="col-md-4"><input name="shipping_cost" type="number" min="0" class="form-control" placeholder="Biaya Kirim" value="{{ old('shipping_cost') }}" required></div>
    <div class="col-12"><textarea name="note" class="form-control" placeholder="Catatan">{{ old('note') }}</textarea></div>
    <div class="col-12">
        <h6>Subtotal: Rp {{ number_format($cart->items->sum('subtotal'), 0, ',', '.') }}</h6>
        <h6>Diskon: Rp {{ number_format($discount ?? 0, 0, ',', '.') }}</h6>
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

    const oldProvince = @json(old('province_id'));
    const oldCity = @json(old('city_id'));
    const oldDistrict = @json(old('district_id'));

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

    provinceSelect.addEventListener('change', async function () {
        syncHiddenText(provinceSelect, provinceName);
        await loadCities(provinceSelect.value);
    });

    citySelect.addEventListener('change', async function () {
        syncHiddenText(citySelect, cityName);
        await loadDistricts(citySelect.value);
    });

    districtSelect.addEventListener('change', function () {
        syncHiddenText(districtSelect, districtName);
    });

    if (oldProvince) {
        await loadCities(oldProvince, oldCity);
    }

    if (oldCity) {
        await loadDistricts(oldCity, oldDistrict);
    }
});
</script>
@endsection
