<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTypeImgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $data = [
            'box' => [
                'imgPath' => 'product/Box.jpg',
                'withPalletImgPath' => 'product/pallet_with_boxes.jpg'
            ],
            'bag' => [
                'imgPath' => 'product/Bag.jpg',
                'withPalletImgPath' => 'product/pallet_with_bag.jpg'
            ],
            'pail' => [
                'imgPath' => 'product/Pail.jpg',
                'withPalletImgPath' => 'product/pallet_with_pails.jpg'
            ],
            'tote' => [
                'imgPath' => 'product/Tote.jpg',
                'withPalletImgPath' => 'product/pallet_with_totes.jpg'
            ],
            'tray' => [
               'imgPath' => 'product/Tray.jpg',
               'withPalletImgPath' => 'product/pallet_with_trays.jpg'
            ]
       ];

       foreach ($data as $productSlug => $pathArr) {
           ProductType::firstWhere('slug', $productSlug)->update([
               'img_path' => $pathArr['imgPath'],
               'img_with_pallet_path' => $pathArr['withPalletImgPath']
           ]);
       }
    }
}
