<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Customer;
use App\Models\SalesRepresentative;
use App\Models\User;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        // Create additional users if they don't exist
        if (!User::where('email', 'manager@admin.com')->exists()) {
            User::create([
                'name' => 'مدير النظام',
                'email' => 'manager@admin.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'phone' => '01234567891',
                'address' => 'الجيزة، مصر',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        if (!User::where('email', 'sales@admin.com')->exists()) {
            $salesUser = User::create([
                'name' => 'مندوب مبيعات',
                'email' => 'sales@admin.com',
                'password' => bcrypt('password'),
                'role' => 'sales_representative',
                'phone' => '01234567892',
                'address' => 'الإسكندرية، مصر',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        } else {
            $salesUser = User::where('email', 'sales@admin.com')->first();
        }

        // Create sales representative record if it doesn't exist
        if (!SalesRepresentative::where('user_id', $salesUser->id)->exists()) {
            SalesRepresentative::create([
                'name' => 'أحمد محمد علي',
                'phone' => '01234567892',
                'address' => 'الإسكندرية، مصر',
                'balance' => 0.0,
                'is_active' => true,
                'user_id' => $salesUser->id,
            ]);
        }

        // Create suppliers
        $suppliers = [
            [
                'name' => 'شركة المنظفات المتحدة',
                'phone' => '01001234567',
                'email' => 'info@cleaners-united.com',
                'address' => 'القاهرة الجديدة، القاهرة',
                'balance' => 0.0,
                'is_active' => true,
            ],
            [
                'name' => 'مصنع الكيماويات الحديثة',
                'phone' => '01002345678',
                'email' => 'contact@modern-chemicals.com',
                'address' => 'العبور، القليوبية',
                'balance' => 0.0,
                'is_active' => true,
            ],
            [
                'name' => 'شركة النظافة الذهبية',
                'phone' => '01003456789',
                'email' => 'info@golden-clean.com',
                'address' => 'برج العرب، الإسكندرية',
                'balance' => 0.0,
                'is_active' => true,
            ]
        ];

        foreach ($suppliers as $supplierData) {
            if (!Supplier::where('email', $supplierData['email'])->exists()) {
                Supplier::create($supplierData);
            }
        }

        // Create products
        $supplier1 = Supplier::first();
        $supplier2 = Supplier::skip(1)->first();
        $supplier3 = Supplier::skip(2)->first();

        $products = [
            [
                'name' => 'مسحوق غسيل فايبر 3 كيلو',
                'description' => 'مسحوق غسيل عالي الجودة للغسالات العادية والأوتوماتيك',
                'barcode' => '1234567890123',
                'supplier_id' => $supplier1->id,
                'units_per_carton' => 12,
                'stock_cartons' => 8,
                'stock_units' => 4,
                'purchase_price' => 35.00,
                'wholesale_price' => 42.00,
                'retail_price' => 45.00,
                'is_active' => true,
            ],
            [
                'name' => 'صابون سائل للأطباق ليمون 1 لتر',
                'description' => 'صابون سائل برائحة الليمون لتنظيف الأطباق',
                'barcode' => '1234567890124',
                'supplier_id' => $supplier1->id,
                'units_per_carton' => 20,
                'stock_cartons' => 4,
                'stock_units' => 0,
                'purchase_price' => 12.50,
                'wholesale_price' => 16.00,
                'retail_price' => 18.00,
                'is_active' => true,
            ],
            [
                'name' => 'معطر أرضيات لافندر 2 لتر',
                'description' => 'معطر أرضيات برائحة اللافندر المنعشة',
                'barcode' => '1234567890125',
                'supplier_id' => $supplier2->id,
                'units_per_carton' => 15,
                'stock_cartons' => 4,
                'stock_units' => 0,
                'purchase_price' => 25.00,
                'wholesale_price' => 32.00,
                'retail_price' => 35.00,
                'is_active' => true,
            ],
            [
                'name' => 'مطهر متعدد الاستخدامات 500 مل',
                'description' => 'مطهر قوي لجميع الأسطح',
                'barcode' => '1234567890126',
                'supplier_id' => $supplier2->id,
                'units_per_carton' => 24,
                'stock_cartons' => 1,
                'stock_units' => 21,
                'purchase_price' => 18.00,
                'wholesale_price' => 23.00,
                'retail_price' => 25.00,
                'is_active' => true,
            ],
            [
                'name' => 'منظف حمامات قوي 750 مل',
                'description' => 'منظف خاص للحمامات وإزالة الجير',
                'barcode' => '1234567890127',
                'supplier_id' => $supplier3->id,
                'units_per_carton' => 18,
                'stock_cartons' => 1,
                'stock_units' => 17,
                'purchase_price' => 22.00,
                'wholesale_price' => 28.00,
                'retail_price' => 30.00,
                'is_active' => true,
            ],
            [
                'name' => 'مسحوق غسيل أطفال 2 كيلو',
                'description' => 'مسحوق غسيل لطيف للأطفال وذوي البشرة الحساسة',
                'barcode' => '1234567890128',
                'supplier_id' => $supplier1->id,
                'units_per_carton' => 10,
                'stock_cartons' => 2,
                'stock_units' => 5,
                'purchase_price' => 40.00,
                'wholesale_price' => 50.00,
                'retail_price' => 55.00,
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            if (!Product::where('barcode', $productData['barcode'])->exists()) {
                Product::create($productData);
            }
        }

        // Create customers
        $customers = [
            [
                'name' => 'محمد أحمد علي - سوبر ماركت الأمل',
                'phone' => '01012345678',
                'email' => 'mohamed@alamal-market.com',
                'address' => 'شارع الجلاء، وسط البلد، القاهرة',
                'price_type' => 'wholesale',
                'balance' => 0.0,
                'is_active' => true,
            ],
            [
                'name' => 'فاطمة حسن محمد - متجر النور للمنظفات',
                'phone' => '01023456789',
                'email' => 'fatma@alnoor-store.com',
                'address' => 'شارع الحرية، الإسكندرية',
                'price_type' => 'retail',
                'balance' => 0.0,
                'is_active' => true,
            ],
            [
                'name' => 'أحمد محمود السيد - توزيعات السلام',
                'phone' => '01034567890',
                'email' => 'ahmed@alsalam-dist.com',
                'address' => 'شارع المدينة المنورة، الجيزة',
                'price_type' => 'wholesale',
                'balance' => 0.0,
                'is_active' => true,
            ],
            [
                'name' => 'نادية علي حسن - مؤسسة البركة التجارية',
                'phone' => '01045678901',
                'email' => 'nadia@albaraka-trade.com',
                'address' => 'شارع الثورة، طنطا، الغربية',
                'price_type' => 'wholesale',
                'balance' => 0.0,
                'is_active' => true,
            ],
            [
                'name' => 'خالد يوسف أحمد - شركة الوفاء للتجارة',
                'phone' => '01056789012',
                'email' => 'khaled@alwafa-trading.com',
                'address' => 'شارع سعد زغلول، المنصورة، الدقهلية',
                'price_type' => 'retail',
                'balance' => 0.0,
                'is_active' => true,
            ],
        ];

        foreach ($customers as $customerData) {
            if (!Customer::where('email', $customerData['email'])->exists()) {
                Customer::create($customerData);
            }
        }

        echo "تم إنشاء البيانات التجريبية بنجاح!\n";
        echo "المستخدمين: " . User::count() . "\n";
        echo "الموردين: " . Supplier::count() . "\n";
        echo "المنتجات: " . Product::count() . "\n";
        echo "العملاء: " . Customer::count() . "\n";
        echo "مندوبي المبيعات: " . SalesRepresentative::count() . "\n";
    }
}
