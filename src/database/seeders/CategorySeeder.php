<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'ファッション' => ['メンズ', 'レディース', 'キッズ', 'ベビー', 'アクセサリー', 'バッグ', '靴'],
            '家電・スマホ' => ['スマートフォン', 'テレビ', '冷蔵庫', '洗濯機', 'カメラ', 'イヤホン'],
            '本・音楽・ゲーム' => ['本', '漫画', 'CD', 'DVD', 'ゲーム機', 'ゲームソフト'],
            'おもちゃ・ホビー' => ['フィギュア', 'プラモデル', 'トレカ', 'ラジコン'],
            'スポーツ・レジャー' => ['ゴルフ', '野球', 'サッカー', 'テニス', 'アウトドア', '釣り'],
            '自動車・オートバイ' => ['自動車本体', 'バイク', 'パーツ', 'アクセサリー'],
            'インテリア・住まい・小物' => ['家具', '家電', 'キッチン', '寝具', '日用品'],
            'ハンドメイド' => ['アクセサリー', 'バッグ', '財布', 'インテリア'],
            'チケット' => ['音楽', 'スポーツ', '演劇', '映画', 'イベント'],
            'ベビー・キッズ' => ['ベビー服', 'キッズ服', 'おもちゃ', 'マタニティ', '授乳グッズ'],
        ];

        foreach ($categories as $parentName => $children) {
            $parent = Category::create(['name' => $parentName]);

            foreach ($children as $childName) {
                Category::create([
                    'name' => $childName,
                    'parent_id' => $parent->id,
                ]);
            }
        }
    }
}
