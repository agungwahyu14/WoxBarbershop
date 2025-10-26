<?php
/**
 * Test Script untuk Logic Button Payment di Booking Show
 * 
 * Test ini mensimulasikan kondisi-kondisi berbeda untuk memastikan
 * button yang tepat ditampilkan sesuai dengan status booking dan transaksi.
 */

// Simulasi kondisi booking dan transaksi
$testCases = [
    [
        'name' => 'Booking Pending + Payment Bank + No Transaction',
        'booking' => [
            'id' => 1,
            'status' => 'pending',
            'payment_method' => 'bank',
            'transaction' => null
        ],
        'expected_button' => 'pay_now',
        'expected_cancel_button' => true
    ],
    [
        'name' => 'Booking Pending + Payment Bank + Transaction Pending',
        'booking' => [
            'id' => 1,
            'status' => 'pending',
            'payment_method' => 'bank',
            'transaction' => [
                'transaction_status' => 'pending'
            ]
        ],
        'expected_button' => 'view_transaction',
        'expected_cancel_button' => true
    ],
    [
        'name' => 'Booking Confirmed + Payment Bank + Transaction Settlement',
        'booking' => [
            'id' => 1,
            'status' => 'confirmed',
            'payment_method' => 'bank',
            'transaction' => [
                'transaction_status' => 'settlement'
            ]
        ],
        'expected_button' => 'view_transaction',
        'expected_cancel_button' => false // Cancel button tidak muncul untuk confirmed
    ],
    [
        'name' => 'Booking Pending + Payment Cash + No Transaction',
        'booking' => [
            'id' => 1,
            'status' => 'pending',
            'payment_method' => 'cash',
            'transaction' => null
        ],
        'expected_button' => 'pay_cash',
        'expected_cancel_button' => true
    ],
    [
        'name' => 'Booking Pending + Payment Cash + Transaction Pending',
        'booking' => [
            'id' => 1,
            'status' => 'pending',
            'payment_method' => 'cash',
            'transaction' => [
                'transaction_status' => 'pending'
            ]
        ],
        'expected_button' => 'view_transaction',
        'expected_cancel_button' => true
    ],
    [
        'name' => 'Booking Completed',
        'booking' => [
            'id' => 1,
            'status' => 'completed',
            'payment_method' => 'bank',
            'transaction' => [
                'transaction_status' => 'settlement'
            ]
        ],
        'expected_button' => 'none',
        'expected_cancel_button' => false
    ],
    [
        'name' => 'Booking Cancelled',
        'booking' => [
            'id' => 1,
            'status' => 'cancelled',
            'payment_method' => 'bank',
            'transaction' => null
        ],
        'expected_button' => 'none',
        'expected_cancel_button' => false
    ]
];

function simulateButtonLogic($booking) {
    $result = [
        'payment_button' => 'none',
        'cancel_button' => false
    ];
    
    // Logic untuk cancel button
    if (!in_array($booking['status'], ['completed', 'cancelled', 'confirmed'])) {
        $result['cancel_button'] = true;
    }
    
    // Logic untuk payment button
    if (!in_array($booking['status'], ['completed', 'cancelled'])) {
        if ($booking['payment_method'] === 'bank') {
            if ($booking['transaction'] && in_array($booking['transaction']['transaction_status'], ['pending', 'settlement'])) {
                $result['payment_button'] = 'view_transaction';
            } else {
                $result['payment_button'] = 'pay_now';
            }
        } elseif ($booking['payment_method'] === 'cash') {
            if (!$booking['transaction'] || !in_array($booking['transaction']['transaction_status'], ['settlement', 'pending'])) {
                $result['payment_button'] = 'pay_cash';
            } elseif ($booking['transaction']) {
                $result['payment_button'] = 'view_transaction';
            }
        }
    }
    
    return $result;
}

echo "🧪 Testing Button Logic for Booking Show Page\n";
echo "=" . str_repeat("=", 60) . "\n\n";

foreach ($testCases as $i => $testCase) {
    echo "Test Case " . ($i + 1) . ": " . $testCase['name'] . "\n";
    echo str_repeat("-", 50) . "\n";
    
    $result = simulateButtonLogic($testCase['booking']);
    
    // Check payment button
    $paymentButtonMatch = ($result['payment_button'] === $testCase['expected_button']);
    $cancelButtonMatch = ($result['cancel_button'] === $testCase['expected_cancel_button']);
    
    echo "Booking Status: " . $testCase['booking']['status'] . "\n";
    echo "Payment Method: " . $testCase['booking']['payment_method'] . "\n";
    echo "Transaction Status: " . ($testCase['booking']['transaction'] ? $testCase['booking']['transaction']['transaction_status'] : 'none') . "\n\n";
    
    echo "Expected Payment Button: " . $testCase['expected_button'] . "\n";
    echo "Actual Payment Button: " . $result['payment_button'] . "\n";
    echo "Payment Button Test: " . ($paymentButtonMatch ? "✅ PASS" : "❌ FAIL") . "\n\n";
    
    echo "Expected Cancel Button: " . ($testCase['expected_cancel_button'] ? 'visible' : 'hidden') . "\n";
    echo "Actual Cancel Button: " . ($result['cancel_button'] ? 'visible' : 'hidden') . "\n";
    echo "Cancel Button Test: " . ($cancelButtonMatch ? "✅ PASS" : "❌ FAIL") . "\n\n";
    
    echo "Overall: " . (($paymentButtonMatch && $cancelButtonMatch) ? "✅ PASS" : "❌ FAIL") . "\n";
    echo str_repeat("=", 60) . "\n\n";
}

echo "📝 Button Behavior Summary:\n";
echo "- 'Bayar Sekarang' button: Shows when no transaction exists for bank payment\n";
echo "- 'Lihat Transaksi' button: Shows when transaction exists (pending/settlement)\n";
echo "- 'Bayar Tunai' button: Shows when no transaction exists for cash payment\n";
echo "- Cancel button: Hidden when status is 'completed', 'cancelled', or 'confirmed'\n";
echo "- All payment buttons hidden when status is 'completed' or 'cancelled'\n\n";

echo "🔧 Implementation Details:\n";
echo "- Route used for 'Lihat Transaksi': route('payment.show', \$booking->id)\n";
echo "- Translation key added: __('booking.view_transaction')\n";
echo "- Cancel button now excludes 'confirmed' status\n";