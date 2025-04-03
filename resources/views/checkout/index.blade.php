<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Finalizar compra') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Resumen del pedido -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Resumen del pedido</h3>
                            
                            <div class="border rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Producto
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Cantidad
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Precio
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @php 
                                            $subtotal = 0; 
                                        @endphp
                                        @foreach($cart->items as $item)
                                            @php 
                                                $itemTotal = $item->product->price * $item->quantity;
                                                $subtotal += $itemTotal;
                                            @endphp
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $item->product->name }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    ${{ number_format($itemTotal, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        @php
                                            $tax = $subtotal * 0.16;
                                            $total = $subtotal + $tax;
                                        @endphp
                                        <tr>
                                            <td colspan="2" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Subtotal:
                                            </td>
                                            <td class="px-6 py-3 text-sm font-medium">
                                                ${{ number_format($subtotal, 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Impuestos (16%):
                                            </td>
                                            <td class="px-6 py-3 text-sm font-medium">
                                                ${{ number_format($tax, 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Total:
                                            </td>
                                            <td class="px-6 py-3 text-sm font-bold">
                                                ${{ number_format($total, 2) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Formulario de pago -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Información de pago</h3>
                            
                            <form action="{{ route('checkout.process') }}" method="POST">
                                @csrf
                                
                                <!-- Información de envío -->
                                <div class="mb-6">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre completo</label>
                                    <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                
                                <div class="mb-6">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Dirección de envío</label>
                                    <textarea name="address" id="address" rows="3" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                                </div>
                                
                                <!-- Información de tarjeta (simulada) -->
                                <div class="mb-6">
                                    <label for="card" class="block text-sm font-medium text-gray-700">Número de tarjeta</label>
                                    <input type="text" name="card" id="card" placeholder="4242 4242 4242 4242" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <p class="mt-1 text-xs text-gray-500">* Este es un proceso simulado, no se guardará información real de tarjetas</p>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <div>
                                        <label for="expiry" class="block text-sm font-medium text-gray-700">Fecha de expiración</label>
                                        <input type="text" name="expiry" id="expiry" placeholder="MM/AA" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label for="cvv" class="block text-sm font-medium text-gray-700">CVV</label>
                                        <input type="text" name="cvv" id="cvv" placeholder="123" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                </div>
                                
                                <div class="mt-8">
                                    <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Completar pago (${{ number_format($total, 2) }})
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>