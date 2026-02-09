<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Product([
            'product_title'        => $row['title'],
            'slug'                 => Str::slug($row['title']),
            'product_type'         => $row['type'], // 0 or 1
            'short_description'    => $row['short_description'],
            'product_decscription' => $row['description'],
            'price'                => $row['price'],
            'discount'             => $row['discount'] ?? 0,
            'exchangeable'         => $row['exchangeable'] ?? 0,
            'refundable'           => $row['refundable'] ?? 0,
            'status'               => $row['status'] ?? 1,
            'visibility'           => $row['visibility'] ?? 1,
        ]);
    }
}