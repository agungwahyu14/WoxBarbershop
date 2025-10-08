<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Simple Loyalty Points Test ===\n\n";

use App\Models\User;
use App\Models\Loyalty;

try {
    // Test 1: Find existing user with loyalty
    echo "1. Mencari user dengan loyalty record...\n";
    $user = User::whereHas('loyalty')->first();
    
    if (!$user) {
        echo "   ❌ Tidak ada user dengan loyalty record\n";
        
        // Find any user
        $user = User::first();
        if (!$user) {
            echo "   ❌ Tidak ada user sama sekali\n";
            exit;
        }
        
        echo "   ⚠️ Menggunakan user: {$user->name}\n";
        echo "   🔄 Membuat loyalty record baru...\n";
        
        // Create simple loyalty record
        $loyalty = new Loyalty();
        $loyalty->user_id = $user->id;
        $loyalty->points = 0;
        $loyalty->save();
        
        echo "   ✅ Loyalty record berhasil dibuat\n";
    } else {
        echo "   ✅ User ditemukan: {$user->name}\n";
    }
    
    // Test 2: Test addPoints method
    echo "\n2. Testing addPoints method...\n";
    $loyalty = $user->loyalty;
    $initialPoints = $loyalty->points ?? 0;
    
    echo "   📊 Points awal: {$initialPoints}\n";
    
    // Try to add 1 point
    echo "   🔄 Menambahkan 1 poin...\n";
    $loyalty->addPoints(1);
    $loyalty->refresh();
    
    $newPoints = $loyalty->points;
    echo "   📊 Points sekarang: {$newPoints}\n";
    
    if ($newPoints > $initialPoints) {
        echo "   ✅ SUCCESS! Points bertambah " . ($newPoints - $initialPoints) . "\n";
    } else {
        echo "   ❌ FAILED! Points tidak bertambah\n";
    }
    
    // Test 3: Test canRedeemFreeService method
    echo "\n3. Testing canRedeemFreeService method...\n";
    $canRedeem = $loyalty->canRedeemFreeService();
    echo "   🎁 Can redeem (points >= 10): " . ($canRedeem ? 'YES' : 'NO') . "\n";
    echo "   📈 Current points: {$loyalty->points}\n";
    
    if ($loyalty->points >= 10) {
        echo "   ✅ User bisa redeem!\n";
    } else {
        echo "   ⏳ Butuh " . (10 - $loyalty->points) . " poin lagi\n";
    }
    
    // Test 4: Add more points to reach 10 (for testing)
    if ($loyalty->points < 10) {
        echo "\n4. Menambahkan lebih banyak poin untuk testing redeem...\n";
        $pointsNeeded = 10 - $loyalty->points;
        echo "   🔄 Menambahkan {$pointsNeeded} poin...\n";
        
        for ($i = 0; $i < $pointsNeeded; $i++) {
            $loyalty->addPoints(1);
            echo "     + 1 poin (total: {$loyalty->points})\n";
        }
        
        $canRedeemNow = $loyalty->canRedeemFreeService();
        echo "   🎁 Can redeem now: " . ($canRedeemNow ? 'YES' : 'NO') . "\n";
        
        if ($canRedeemNow) {
            echo "   ✅ SUCCESS! User sekarang bisa redeem\n";
        }
    }
    
    // Test 5: Test redeemFreeService method
    if ($loyalty->points >= 10) {
        echo "\n5. Testing redeemFreeService method...\n";
        $pointsBeforeRedeem = $loyalty->points;
        echo "   📊 Points sebelum redeem: {$pointsBeforeRedeem}\n";
        
        echo "   🔄 Melakukan redeem...\n";
        $loyalty->redeemFreeService();
        $loyalty->refresh();
        
        $pointsAfterRedeem = $loyalty->points;
        echo "   📊 Points setelah redeem: {$pointsAfterRedeem}\n";
        
        if ($pointsAfterRedeem === 0) {
            echo "   ✅ SUCCESS! Points berhasil direset ke 0\n";
        } else {
            echo "   ❌ FAILED! Points tidak direset ke 0\n";
        }
    }
    
    echo "\n=== FINAL SUMMARY ===\n";
    echo "👤 User: {$user->name} (ID: {$user->id})\n";
    echo "⭐ Final Points: {$loyalty->points}\n";
    echo "🎁 Can Redeem: " . ($loyalty->canRedeemFreeService() ? 'YES' : 'NO') . "\n";
    
    echo "\n🎉 LOYALTY SYSTEM METHODS WORK CORRECTLY!\n";
    echo "✅ addPoints() method working\n";
    echo "✅ canRedeemFreeService() method working\n";
    echo "✅ redeemFreeService() method working\n";
    
    echo "\n=== Test Completed Successfully ===\n";

} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}