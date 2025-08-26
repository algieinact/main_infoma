<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Voucher;
use App\Models\Residence;
use App\Models\Activity;
use App\Models\User;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some providers, residences, and activities
        $providers = User::where('role', 'provider')->take(3)->get();
        $residences = Residence::take(2)->get();
        $activities = Activity::take(2)->get();

        if ($providers->isEmpty() || $residences->isEmpty() || $activities->isEmpty()) {
            $this->command->info('Skipping VoucherSeeder: Not enough providers, residences, or activities found.');
            return;
        }

        // Create vouchers for residences
        foreach ($residences as $residence) {
            $provider = $residence->provider;
            
            // Percentage discount voucher
            Voucher::create([
                'code' => 'RES' . strtoupper(substr($residence->title, 0, 3)) . '10',
                'provider_id' => $provider->id,
                'discountable_type' => Residence::class,
                'discountable_id' => $residence->id,
                'discount_type' => 'percentage',
                'discount_value' => 10.00,
                'min_purchase' => 500000,
                'max_discount' => 100000,
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
                'usage_limit' => 50,
                'used_count' => 0,
                'is_active' => true,
                'description' => 'Diskon 10% untuk ' . $residence->title
            ]);

            // Fixed amount voucher
            Voucher::create([
                'code' => 'RES' . strtoupper(substr($residence->title, 0, 3)) . '50K',
                'provider_id' => $provider->id,
                'discountable_type' => Residence::class,
                'discountable_id' => $residence->id,
                'discount_type' => 'fixed',
                'discount_value' => 50000,
                'min_purchase' => 1000000,
                'max_discount' => null,
                'start_date' => now(),
                'end_date' => now()->addMonths(2),
                'usage_limit' => 30,
                'used_count' => 0,
                'is_active' => true,
                'description' => 'Potongan langsung Rp 50.000 untuk ' . $residence->title
            ]);
        }

        // Create vouchers for activities
        foreach ($activities as $activity) {
            $provider = $activity->provider;
            
            // Percentage discount voucher
            Voucher::create([
                'code' => 'ACT' . strtoupper(substr($activity->title, 0, 3)) . '15',
                'provider_id' => $provider->id,
                'discountable_type' => Activity::class,
                'discountable_id' => $activity->id,
                'discount_type' => 'percentage',
                'discount_value' => 15.00,
                'min_purchase' => 100000,
                'max_discount' => 75000,
                'start_date' => now(),
                'end_date' => now()->addMonths(4),
                'usage_limit' => 100,
                'used_count' => 0,
                'is_active' => true,
                'description' => 'Diskon 15% untuk ' . $activity->title
            ]);

            // Fixed amount voucher
            Voucher::create([
                'code' => 'ACT' . strtoupper(substr($activity->title, 0, 3)) . '25K',
                'provider_id' => $provider->id,
                'discountable_type' => Activity::class,
                'discountable_id' => $activity->id,
                'discount_type' => 'fixed',
                'discount_value' => 25000,
                'min_purchase' => 200000,
                'max_discount' => null,
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
                'usage_limit' => 75,
                'used_count' => 0,
                'is_active' => true,
                'description' => 'Potongan langsung Rp 25.000 untuk ' . $activity->title
            ]);
        }

        // Create some expired vouchers for testing
        $expiredResidence = $residences->first();
        if ($expiredResidence) {
            Voucher::create([
                'code' => 'EXPIRED' . strtoupper(substr($expiredResidence->title, 0, 3)),
                'provider_id' => $expiredResidence->provider->id,
                'discountable_type' => Residence::class,
                'discountable_id' => $expiredResidence->id,
                'discount_type' => 'percentage',
                'discount_value' => 20.00,
                'min_purchase' => 0,
                'max_discount' => null,
                'start_date' => now()->subMonths(2),
                'end_date' => now()->subMonth(),
                'usage_limit' => 20,
                'used_count' => 0,
                'is_active' => true,
                'description' => 'Voucher expired untuk testing'
            ]);
        }

        $this->command->info('Vouchers created successfully!');
        $this->command->info('Sample voucher codes:');
        $this->command->info('- RESxxx10 (10% discount for residences)');
        $this->command->info('- RESxxx50K (Rp 50.000 discount for residences)');
        $this->command->info('- ACTxxx15 (15% discount for activities)');
        $this->command->info('- ACTxxx25K (Rp 25.000 discount for activities)');
    }
}
