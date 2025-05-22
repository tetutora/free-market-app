<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'Nike', 'Adidas', 'Puma', 'Reebok', 'New Balance',
            'Under Armour', 'Asics', 'Champion', 'Converse', 'Vans',
            'Levi\'s', 'GU', 'UNIQLO', 'ZARA', 'H&M',
            'GAP', 'Tommy Hilfiger', 'Ralph Lauren', 'Lacoste', 'Calvin Klein',
            'The North Face', 'Columbia', 'Patagonia', 'Montbell', 'Arc\'teryx',
            'GUCCI', 'PRADA', 'Louis Vuitton', 'CHANEL', 'Dior',
            'FENDI', 'BALENCIAGA', 'BURBERRY', 'Hermès', 'Yves Saint Laurent',
            'Supreme', 'Off-White', 'Stüssy', 'A BATHING APE', 'WTAPS',
            'BEAMS', 'UNITED ARROWS', 'nano・universe', 'SHIPS', 'journal standard',
            '無印良品', 'しまむら', 'WEGO', 'earth music&ecology', 'snidel'
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(['name' => $brand]);
        }
    }
}
