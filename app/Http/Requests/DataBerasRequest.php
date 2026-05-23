<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DataBerasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        $id = $this->route('data_bera');

        return [
            'tahun' => 'required|digits:4|integer|min:2000',
            'bulan' => [
                'required',
                'integer',
                'between:1,12',
                Rule::unique('data_beras', 'bulan')
                    ->where(function ($query) {
                        return $query->where('tahun', $this->tahun);
                    })
                    ->ignore($id),
            ],
            'produksi_ton' => 'required|numeric|min:0',
            'stok_awal_ton' => 'nullable|numeric|min:0',
            'impor_ton' => 'nullable|numeric|min:0',
            'ekspor_ton' => 'nullable|numeric|min:0',
            'konsumsi_ton' => 'required|numeric|min:0',
            'ketersediaan_ton' => 'required|numeric',
            'sumber_data' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'tahun.unique' => 'Data untuk tahun tersebut sudah ada di database.',
            'produksi_ton.min' => 'Produksi tidak boleh bernilai negatif.',
        ];
    }
}