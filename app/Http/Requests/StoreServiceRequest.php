<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    /**
     * Daftar wilayah operasional Home Service.
     * Kalau ada perubahan wilayah, cukup edit di sini.
     */
    public const VALID_REGIONS = [
        // Kota Bukittinggi
        'Mandiangin Koto Selayan',
        'Guguk Panjang',
        'Aur Birugo Tigo Baleh',
        // Kabupaten Agam & Sekitarnya
        'Ampek Angkek',
        'Baso',
        'Banuhampu',
        'Tilatang Kamang',
        'Sungai Pua',
        'Canduang',
        'IV Koto',
        'Padang Lua',
        'Sungai Landia',
        'Gantiang',
        'Ladang Laweh',
        'Cingkariang',
        'Pandai Sikek',
    ];

    public function authorize(): bool
    {
        // Sudah dijaga middleware auth:customer di route, jadi selalu true di sini
        return true;
    }

    public function rules(): array
    {
        return [
            'service_type' => ['required', 'in:Workshop,Home Service'],
            'item_name' => ['required', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:50'],
            'complaint' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:512000'],

            'region' => [
                'required_if:service_type,Home Service',
                'nullable',
                Rule::in(self::VALID_REGIONS),
            ],
            'address' => ['required_if:service_type,Home Service', 'nullable', 'string'],
            'latitude' => ['required_if:service_type,Home Service', 'nullable', 'numeric'],
            'longitude' => ['required_if:service_type,Home Service', 'nullable', 'numeric'],
            'preferred_visit_date' => ['required_if:service_type,Home Service', 'nullable', 'date', 'after_or_equal:today'],
            'preferred_visit_time' => ['required_if:service_type,Home Service', 'nullable', 'in:Pagi (08.00-11.00),Siang (11.00-14.00),Sore (14.00-17.00)'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'region.required_if' => 'Wilayah Home Service wajib dipilih.',
            'region.in' => 'Wilayah yang dipilih berada di luar area layanan Home Service. Silakan gunakan layanan Workshop.',
            'latitude.required_if' => 'Titik lokasi pada peta wajib dipilih.',
            'longitude.required_if' => 'Titik lokasi pada peta wajib dipilih.',
            'preferred_visit_date.after_or_equal' => 'Tanggal kunjungan tidak boleh sebelum hari ini.',
        ];
    }
}