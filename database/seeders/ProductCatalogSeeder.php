<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCatalogSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            // Hanya bersihkan data contoh bawaan aplikasi, bukan transaksi nyata.
            Transaction::where('invoice_number', 'like', 'TRX-DEMO-%')->delete();
            Product::whereIn('sku', ['AP-001', 'AP-002', 'AP-003', 'PN-001', 'PN-002', 'PM-001', 'PM-002'])->delete();
            Category::doesntHave('products')->delete();

            $categories = collect([
                ['code' => 'NHF', 'name' => 'New Hope Floating', 'color' => '#0f766e'],
                ['code' => 'HPF', 'name' => 'Hiv ProVite Floating', 'color' => '#0891b2'],
                ['code' => 'PFF', 'name' => 'Prima Feed Floating', 'color' => '#2563eb'],
                ['code' => 'BTG', 'name' => 'Bintang Tenggelam', 'color' => '#7c3aed'],
                ['code' => 'NEO', 'name' => 'Neo Floating', 'color' => '#db2777'],
                ['code' => 'PAB', 'name' => 'Pakan Ayam & Bebek', 'color' => '#ea580c'],
                ['code' => 'PKC', 'name' => 'Pakan Kucing', 'color' => '#ca8a04'],
                ['code' => 'PBR', 'name' => 'Pakan Burung', 'color' => '#65a30d'],
            ])->keyBy('code')->map(function (array $category): Category {
                return Category::updateOrCreate(
                    ['name' => $category['name']],
                    ['type' => 'pakan_ikan', 'color' => $category['color']],
                );
            });

            foreach ($this->products() as $item) {
                Product::updateOrCreate(
                    ['sku' => $item[1]],
                    [
                        'category_id' => $categories[$item[0]]->id,
                        'name' => $item[2],
                        'price' => $item[3],
                        'unit' => $item[4],
                        'minimum_stock' => $item[5] ?? 5,
                    ],
                );
            }
        });
    }

    /** @return array<int, array{string, string, string, int, string, int?}> */
    private function products(): array
    {
        return [
            ['NHF', 'NHF-833-1-SAK', '833-1', 418000, 'sak'],
            ['NHF', 'NHF-833-2-SAK', '833-2', 408000, 'sak'],
            ['NHF', 'NHF-833-3-SAK', '833-3', 403000, 'sak'],
            ['NHF', 'NHF-834L-2-SAK', '834L-2', 378000, 'sak'],
            ['NHF', 'NHF-834L-2-SP-SAK', '834L-2 SP', 383000, 'sak'],
            ['NHF', 'NHF-834L-3-SAK', '834L-3', 368000, 'sak'],
            ['NHF', 'NHF-834-3-POLOS-SAK', '834-3 Polos', 363000, 'sak'],
            ['NHF', 'NHF-835-2-SAK', '835-2', 363000, 'sak'],
            ['NHF', 'NHF-835-3-SAK', '835-3', 353000, 'sak'],
            ['NHF', 'NHF-VICTORY-MERAH-SAK', 'Victory Merah', 628000, 'sak'],
            ['NHF', 'NHF-VICTORY-BIRU-SAK', 'Victory Biru', 578000, 'sak'],
            ['NHF', 'NHF-GRANDE-SAK', 'Grande', 538000, 'sak'],
            ['NHF', 'NHF-OMEGA', 'Omega', 0, 'harga belum diisi'],
            ['NHF', 'NHF-TONGWEI', 'Tongwei', 0, 'harga belum diisi'],

            ['HPF', 'HPF-1-20KG-SAK', 'Hiv ProVite 1 (20 kg)', 320000, 'sak'],
            ['HPF', 'HPF-1-ECER', 'Hiv ProVite 1', 18000, 'kg'],
            ['HPF', 'HPF-2-30KG-SAK', 'Hiv ProVite 2 (30 kg)', 430000, 'sak'],
            ['HPF', 'HPF-2-ECER', 'Hiv ProVite 2', 16000, 'kg'],
            ['HPF', 'HPF-3-30KG-SAK', 'Hiv ProVite 3 (30 kg)', 425000, 'sak'],
            ['HPF', 'HPF-3-ECER', 'Hiv ProVite 3', 16000, 'kg'],

            ['PFF', 'PFF-PF0-SAK', 'PF 0', 220000, 'sak'],
            ['PFF', 'PFF-PF0-ECER', 'PF 0', 24000, 'kg'],
            ['PFF', 'PFF-PF100-SAK', 'PF 100', 220000, 'sak'],
            ['PFF', 'PFF-PF100-ECER', 'PF 100', 24000, 'kg'],
            ['PFF', 'PFF-PF500-SAK', 'PF 500', 225000, 'sak'],
            ['PFF', 'PFF-PF500-ECER', 'PF 500', 24000, 'kg'],
            ['PFF', 'PFF-PF800-SAK', 'PF 800', 215000, 'sak'],
            ['PFF', 'PFF-PF800-ECER', 'PF 800', 23000, 'kg'],
            ['PFF', 'PFF-PF1000-SAK', 'PF 1000', 210000, 'sak'],
            ['PFF', 'PFF-PF1000-ECER', 'PF 1000', 22000, 'kg'],

            ['BTG', 'BTG-888-SAK', '888 Bintang', 585000, 'sak'],
            ['BTG', 'BTG-888-ECER', '888 Bintang', 15000, 'kg'],
            ['BTG', 'BTG-333-SAK', '333 Sinking', 435000, 'sak'],
            ['BTG', 'BTG-333-ECER', '333 Sinking', 10000, 'kg'],

            ['NEO', 'NEO-2', 'NEO-2', 0, 'harga belum diisi'],
            ['NEO', 'NEO-3', 'NEO-3', 0, 'harga belum diisi'],
            ['NEO', 'NEO-5', 'NEO-5', 0, 'harga belum diisi'],
            ['NEO', 'NEO-SUPRA-3', 'SUPRA-3', 0, 'harga belum diisi'],

            ['PAB', 'PAB-511-SAK', '511', 530000, 'sak'],
            ['PAB', 'PAB-511-ECER', '511', 12000, 'kg'],
            ['PAB', 'PAB-512-SAK', '512', 530000, 'sak'],
            ['PAB', 'PAB-512-ECER', '512', 12000, 'kg'],
            ['PAB', 'PAB-551-BABI-SAK', '551 Babi', 570000, 'sak'],
            ['PAB', 'PAB-551-BABI-ECER', '551 Babi', 13000, 'kg'],
            ['PAB', 'PAB-591-SAK', '591', 240000, 'sak'],
            ['PAB', 'PAB-591-ECER', '591', 14000, 'kg'],
            ['PAB', 'PAB-594-SAK', '594', 235000, 'sak'],
            ['PAB', 'PAB-594-ECER', '594', 14000, 'kg'],
            ['PAB', 'PAB-SINTA-BIRU-SAK', 'Sinta Biru', 450000, 'sak'],
            ['PAB', 'PAB-SINTA-BIRU-ECER', 'Sinta Biru', 10000, 'kg'],
            ['PAB', 'PAB-SINTA-MERAH-SAK', 'Sinta Merah', 500000, 'sak'],
            ['PAB', 'PAB-SINTA-MERAH-ECER', 'Sinta Merah', 11000, 'kg'],
            ['PAB', 'PAB-GARDA-SAK', 'Garda', 420000, 'sak'],
            ['PAB', 'PAB-GARDA-ECER', 'Garda', 9500, 'kg'],
            ['PAB', 'PAB-GENTA-SAK', 'Genta', 420000, 'sak'],
            ['PAB', 'PAB-GENTA-ECER', 'Genta', 9500, 'kg'],
            ['PAB', 'PAB-BANGSAL', 'Bangsal (Padi Merah)', 13000, 'kg'],
            ['PAB', 'PAB-JAGUNG-CAMPUR', 'Jagung Campur', 15000, 'kg'],
            ['PAB', 'PAB-JAGUNG-MERAH', 'Jagung Merah', 20000, 'kg'],
            ['PAB', 'PAB-BEUNYEUR-KG', 'Beunyeur', 13000, 'kg'],
            ['PAB', 'PAB-BEUNYEUR-500G', 'Beunyeur', 8000, '500 gr'],

            ['PKC', 'PKC-BOLT-UNGU-KG', 'Bolt Ungu Ikan', 24000, 'kg'],
            ['PKC', 'PKC-BOLT-UNGU-800G', 'Bolt Ungu Ikan', 22000, '800 gr'],
            ['PKC', 'PKC-BOLT-UNGU-500G', 'Bolt Ungu Ikan', 12000, '500 gr'],
            ['PKC', 'PKC-BOLT-KUNING-800G', 'Bolt Kuning', 22000, '800 gr'],
            ['PKC', 'PKC-BOLT-PINK-800G', 'Bolt Pink', 22000, '800 gr'],
            ['PKC', 'PKC-BOLT-KITTEN-500G', 'Bolt Kitten', 14000, '500 gr'],
            ['PKC', 'PKC-EXCEL-500G', 'Excel', 14000, '500 gr'],
            ['PKC', 'PKC-FELIBITE-500G', 'Felibite', 15000, '500 gr'],
            ['PKC', 'PKC-CAT-CHOIZE-KUNING-KG', 'Cat Choize Kuning', 26000, 'kg'],
            ['PKC', 'PKC-CAT-CHOIZE-PINK-KG', 'Cat Choize Pink', 26000, 'kg'],
            ['PKC', 'PKC-CAT-CHOIZE-HIJAU-800G', 'Cat Choize Hijau', 23000, '800 gr'],
            ['PKC', 'PKC-CAT-CHOIZE-ORANGE-800G', 'Cat Choize Orange', 23000, '800 gr'],

            ['PBR', 'PBR-TOPSONG-SAK', 'Topsong', 325000, 'sak'],
            ['PBR', 'PBR-TOPSONG-ECER', 'Topsong', 13000, 'kg'],
            ['PBR', 'PBR-TOPSONG-RLAUT-SAK', 'Topsong R. Laut', 335000, 'sak'],
            ['PBR', 'PBR-TOPSONG-RLAUT-ECER', 'Topsong R. Laut', 14000, 'kg'],
            ['PBR', 'PBR-PHOENIX', 'Phoenix', 11000, 'kg'],
            ['PBR', 'PBR-EBOD-LOVE-BIRD', 'Ebod Love Bird', 9500, 'kg'],
            ['PBR', 'PBR-EBOD-CANARY', 'Ebod Canary', 10000, 'kg'],
            ['PBR', 'PBR-LEOPARD-HIJAU', 'Leopard Hijau', 8500, 'kg'],
            ['PBR', 'PBR-LEOPARD-ORANGE', 'Leopard Orange', 8000, 'kg'],
            ['PBR', 'PBR-GOLD-COIN-CANARY', 'Gold Coin Canary', 13000, 'kg'],
            ['PBR', 'PBR-GOLD-COIN-PERKUTUT', 'Gold Coin Perkutut', 15000, 'kg'],
            ['PBR', 'PBR-BEUNYEUR-KG', 'Beunyeur', 13000, 'kg'],
            ['PBR', 'PBR-BEUNYEUR-500G', 'Beunyeur', 7000, '500 gr'],
            ['PBR', 'PBR-BERAS-MERAH', 'Beras Merah', 0, 'harga belum diisi'],
            ['PBR', 'PBR-JAGUNG-MERAH-KG', 'Jagung Merah', 20000, 'kg'],
            ['PBR', 'PBR-JAGUNG-MERAH-500G', 'Jagung Merah', 10000, '500 gr'],
            ['PBR', 'PBR-KEONG-MAS-KG', 'Keong Mas', 10000, 'kg'],
            ['PBR', 'PBR-KEONG-MAS-500G', 'Keong Mas', 5000, '500 gr'],
        ];
    }
}
