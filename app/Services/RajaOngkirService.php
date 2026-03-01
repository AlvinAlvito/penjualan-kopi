<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    public function provinces(): array
    {
        $rows = $this->isV2()
            ? $this->request('/destination/province')
            : $this->request('/province');

        return collect($rows)->map(fn ($row) => [
            'id' => (string) ($row['province_id'] ?? $row['id'] ?? ''),
            'name' => (string) ($row['province'] ?? $row['name'] ?? ''),
        ])->filter(fn ($row) => $row['id'] !== '' && $row['name'] !== '')->values()->all();
    }

    public function cities(string $provinceId): array
    {
        $rows = $this->isV2()
            ? $this->request('/destination/city/'.urlencode($provinceId))
            : $this->request('/city', ['province' => $provinceId]);

        return collect($rows)->map(function ($row) {
            $cityName = trim((string) (($row['type'] ?? '').' '.($row['city_name'] ?? $row['city'] ?? $row['name'] ?? '')));

            return [
                'id' => (string) ($row['city_id'] ?? $row['id'] ?? ''),
                'name' => $cityName !== '' ? $cityName : (string) ($row['name'] ?? ''),
            ];
        })->filter(fn ($row) => $row['id'] !== '' && $row['name'] !== '')->values()->all();
    }

    public function districts(string $cityId): array
    {
        $rows = $this->isV2()
            ? $this->request('/destination/district/'.urlencode($cityId), [], false)
            : $this->request('/subdistrict', ['city' => $cityId], false);

        if (empty($rows)) {
            return [];
        }

        return collect($rows)->map(fn ($row) => [
            'id' => (string) ($row['subdistrict_id'] ?? $row['district_id'] ?? $row['id'] ?? ''),
            'name' => (string) ($row['subdistrict_name'] ?? $row['district_name'] ?? $row['name'] ?? ''),
        ])->filter(fn ($row) => $row['id'] !== '' && $row['name'] !== '')->values()->all();
    }

    public function shippingOptions(string $originId, string $destinationId, int $weight, string $courier): array
    {
        $apiKey = (string) config('services.rajaongkir.api_key');
        $baseUrl = rtrim((string) config('services.rajaongkir.base_url'), '/');

        if ($apiKey === '' || $baseUrl === '' || $originId === '' || $destinationId === '' || $courier === '') {
            return [];
        }

        $response = Http::timeout(15)
            ->acceptJson()
            ->withHeaders(['key' => $apiKey])
            ->asForm()
            ->post($baseUrl.'/calculate/domestic-cost', [
                'origin' => $originId,
                'destination' => $destinationId,
                'weight' => max(1, $weight),
                'courier' => strtolower($courier),
                'price' => 'lowest',
            ]);

        if (!$response->ok()) {
            return [];
        }

        $json = $response->json();
        $rows = $json['data'] ?? [];
        if (!is_array($rows)) {
            return [];
        }

        return collect($rows)->map(fn ($row) => [
            'courier_name' => (string) ($row['name'] ?? ''),
            'courier_code' => (string) ($row['code'] ?? ''),
            'service' => (string) ($row['service'] ?? ''),
            'description' => (string) ($row['description'] ?? ''),
            'cost' => (int) ($row['cost'] ?? 0),
            'etd' => (string) ($row['etd'] ?? ''),
        ])->filter(fn ($row) => $row['service'] !== '' && $row['cost'] >= 0)->values()->all();
    }

    private function request(string $path, array $query = [], bool $throwIfMissing = true): array
    {
        $apiKey = (string) config('services.rajaongkir.api_key');
        $baseUrl = rtrim((string) config('services.rajaongkir.base_url'), '/');

        if ($apiKey === '' || $baseUrl === '') {
            return [];
        }

        $response = Http::timeout(10)
            ->acceptJson()
            ->withHeaders(['key' => $apiKey])
            ->get($baseUrl.$path, $query);

        if (!$response->ok()) {
            return [];
        }

        $json = $response->json();

        $rows = $json['rajaongkir']['results']
            ?? $json['data']
            ?? $json['results']
            ?? [];

        if (!is_array($rows)) {
            return [];
        }

        if ($throwIfMissing && empty($rows)) {
            return [];
        }

        return $rows;
    }

    private function isV2(): bool
    {
        $baseUrl = strtolower((string) config('services.rajaongkir.base_url'));
        return str_contains($baseUrl, 'komerce.id') || str_contains($baseUrl, '/api/v1');
    }
}
