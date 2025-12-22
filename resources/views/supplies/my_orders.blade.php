@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-6 bg-white rounded-2xl shadow-lg">
    <h1 class="text-4xl font-extrabold text-green-800 mb-8 text-center">My Orders</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 p-4 rounded mb-6 shadow-md">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-800 p-4 rounded mb-6 shadow-md">
            {{ session('error') }}
        </div>
    @endif

    @if($groupedOrders->isEmpty())
        <p class="text-gray-600 text-center text-lg">You have no orders.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-200 rounded-lg shadow-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left border-b">Order</th>
                        <th class="p-3 text-center border-b">Total Price</th>
                        <th class="p-3 text-center border-b">Status</th>
                        <th class="p-3 text-center border-b">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalPrice = 0; $counter = 1; @endphp
                    @foreach($groupedOrders as $orderId => $orderGroup)
                        @php
                            $groupTotal = $orderGroup->sum('total_price');
                            $status = $orderGroup->first()->status;
                            if(in_array($status, ['placed', 'in_way'])) {
                                $totalPrice += $groupTotal;
                            }
                        @endphp
                        <tr class="text-center hover:bg-gray-50 transition">
                            <td class="p-3 text-left border-b">Order {{ $counter++ }}</td>
                            <td class="p-3 border-b">${{ $groupTotal }}</td>
                            <td class="p-3 border-b">
                                @if($status === 'placed')
                                    <span class="px-3 py-1 bg-green-500 text-white rounded-full text-sm">Placed</span>
                                @elseif($status === 'in_way')
                                    <span class="px-3 py-1 bg-blue-500 text-white rounded-full text-sm">In Way</span>
                                @elseif($status === 'paid')
                                    <span class="px-3 py-1 bg-purple-500 text-white rounded-full text-sm">Paid</span>
                                @else
                                    <span class="px-3 py-1 bg-yellow-500 text-white rounded-full text-sm">Pending</span>
                                @endif
                            </td>
                            <td class="p-3 border-b">
                                <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" onclick="showDetails('{{ $orderId }}')">Details</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal for details -->
        <div id="detailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Details</h3>
                    <div id="orderDetailsContent" class="overflow-x-auto"></div>
                    <div class="flex justify-end mt-4">
                        <button class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600" onclick="closeModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end items-center">
            <div class="text-xl font-bold text-gray-800">Total Unpaid: ${{ $totalPrice }}</div>
        </div>
    @endif
</div>

<script>
    const orderDetails = @json($groupedOrders);

    function showDetails(orderId) {
        const orders = orderDetails[orderId];
        let content = '<table class="w-full border border-gray-200 rounded-lg shadow-sm"><thead class="bg-gray-50"><tr><th class="p-3 text-left border-b">Supply</th><th class="p-3 text-center border-b">Quantity</th><th class="p-3 text-center border-b">Total Price</th></tr></thead><tbody>';
        orders.forEach(order => {
            content += `<tr class="text-center hover:bg-gray-50"><td class="p-3 text-left border-b">${order.supply.name}</td><td class="p-3 border-b">${order.quantity}</td><td class="p-3 border-b">$${order.total_price}</td></tr>`;
        });
        content += '</tbody></table>';
        document.getElementById('orderDetailsContent').innerHTML = content;
        document.getElementById('detailsModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('detailsModal').classList.add('hidden');
    }
</script>
@endsection
