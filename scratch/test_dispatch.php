<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Transport;
use App\Models\SupplyOrder;
use App\Services\TransportDispatchAction;
use App\Services\SupplyDispatchAction;

echo "--- Testing Dispatch Logic ---\n";

$transport = Transport::where('status', 'pending')->first();
if ($transport) {
    echo "Testing Transport #{$transport->id} (Gov: {$transport->destination_governorate})...\n";
    $result = TransportDispatchAction::dispatchDriver($transport);
    echo $result ? "SUCCESS: Driver Assigned!\n" : "STAYED PENDING: No driver found in Gov.\n";
} else {
    echo "No pending transports found to test.\n";
}

$order = SupplyOrder::where('status', 'pending')->first();
if ($order) {
    echo "Testing Supply Order #{$order->id} (Gov: " . ($order->booking->farm->governorate ?? 'Unknown') . ")...\n";
    $result = SupplyDispatchAction::dispatchDriver($order);
    echo $result ? "SUCCESS: Driver Assigned!\n" : "STAYED PENDING: No driver found in Gov.\n";
} else {
    echo "No pending orders found to test.\n";
}
