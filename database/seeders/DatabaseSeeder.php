<?php

namespace Database\Seeders;

use App\Models\{Category, Product, StockIn, Transaction, User};
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin=User::updateOrCreate(['username'=>'admin'],['name'=>'Administrator','email'=>'admin@rrjayafishing.test','password'=>'password','role'=>'admin','is_active'=>true]);
        $employee=User::updateOrCreate(['username'=>'karyawan'],['name'=>'Dina Karyawan','email'=>'karyawan@rrjayafishing.test','password'=>'password','role'=>'karyawan','is_active'=>true]);
        User::updateOrCreate(['username'=>'owner'],['name'=>'Pemilik RR Jaya','email'=>'owner@rrjayafishing.test','password'=>'password','role'=>'owner','is_active'=>true]);
        $cats=collect([
            ['name'=>'Alat Pancing','type'=>'alat_pancing','color'=>'#0f766e'],['name'=>'Pakan Ikan Nila','type'=>'pakan_ikan','color'=>'#f59e0b'],['name'=>'Pakan Ikan Mas','type'=>'pakan_ikan','color'=>'#2563eb'],
        ])->map(fn($c)=>Category::firstOrCreate(['name'=>$c['name']],$c));
        $data=[
            [$cats[0]->id,'Joran Carbon River Pro','AP-001',375000,'unit',18,5],[$cats[0]->id,'Reel Spinning RX 3000','AP-002',285000,'unit',12,4],[$cats[0]->id,'Senar Monofilament 500m','AP-003',68000,'roll',25,8],
            [$cats[1]->id,'Pelet Nila Premium 781-2','PN-001',365000,'karung',6,7],[$cats[1]->id,'Pelet Nila Starter','PN-002',18500,'kg',32,10],[$cats[2]->id,'Pelet Ikan Mas Super','PM-001',348000,'karung',4,6],[$cats[2]->id,'Umpan Ikan Mas Aroma Pandan','PM-002',22000,'bungkus',45,10],
        ];
        foreach($data as $p) Product::firstOrCreate(['sku'=>$p[2]],['category_id'=>$p[0],'name'=>$p[1],'price'=>$p[3],'unit'=>$p[4],'stock'=>$p[5],'minimum_stock'=>$p[6]]);
        if(Transaction::count()===0){ foreach(range(1,14) as $day){ $t=Transaction::create(['invoice_number'=>'TRX-DEMO-'.str_pad($day,3,'0',STR_PAD_LEFT),'user_id'=>$employee->id,'customer_name'=>$day%3===0?'Pak Darto':null,'transaction_date'=>now()->subDays(15-$day),'total'=>0]); $total=0; foreach(Product::inRandomOrder()->limit(2)->get() as $product){$qty=random_int(1,3);$sub=$product->price*$qty;$t->items()->create(['product_id'=>$product->id,'quantity'=>$qty,'price'=>$product->price,'subtotal'=>$sub]);$total+=$sub;} $t->update(['total'=>$total]); } }
    }
}
