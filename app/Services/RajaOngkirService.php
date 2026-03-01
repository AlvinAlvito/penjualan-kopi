<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    public function provinces(): array
    {
        $rows = $this->request('/province');

        return collect($rows)->map(fn ($row) => [
            'id' => (string) ($row['province_id'] ?? $row['id'] ?? ''),
            'name' => (string) ($row['province'] ?? $row['name'] ?? ''),
        ])->filter(fn ($row) => $row['id'] !== '' && $row['name'] !== '')->values()->all();
    }

    public function cities(string $provinceId): array
    {
        $rows = $this->request('/city', ['province' => $provinceId]);

        return collect($rows)->map(function ($row) {
            $cityName = trim((string) (($row['type'] ?? '').' '.($row['city_name'] ?? $row['city'] ?? $row['name'] ?? '')));

            return [
                'id' => (string) ($row['city_id'] ?? $row['id'] ?? ''),
                'name' => $cityName,
            ];
        })->filter(fn ($row) => $row['id'] !== '' && $row['name'] !== '')->values()->all();
    }

    public function districts(string $cityId): array
    {
        // Starter RajaOngkir tidak menyediakan kecamatan.
        // Jika memakai endpoint yang mendukung, service ini tetap akan parsing otomatis.
        $rows = $this->request('/subdistrict', ['city' => $cityId], false);

        if (empty($rows)) {
            return [];
        }

        return collect($rows)->map(fn ($row) => [
            'id' => (string) ($row['subdistrict_id'] ?? $row['district_id'] ?? $row['id'] ?? ''),
            'name' => (string) ($row['subdistrict_name'] ?? $row['district_name'] ?? $row['name'] ?? ''),
        ])->filter(fn ($row) => $row['id'] !== '' && $row['name'] !== '')->values()->all();
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
}
