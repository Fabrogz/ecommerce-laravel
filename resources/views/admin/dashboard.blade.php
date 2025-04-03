<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de administración') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Estadísticas generales -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="text-gray-500 text-sm">Total de productos</div>
                        <div class="text-3xl font-bold">{{ $totalProducts }}</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="text-gray-500 text-sm">Total de pedidos</div>
                        <div class="text-3xl font-bold">{{ $totalOrders }}</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="text-gray-500 text-sm">Total de clientes</div>
                        <div class="text-3xl font-bold">{{ $totalUsers }}</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="text-gray-500 text-sm">Ventas totales</div>
                        <div class="text-3xl font-bold">${{ number_format($totalSales, 2) }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Productos más vendidos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Productos más vendidos</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Producto
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Unidades vendidas
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($topProducts as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $product->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $product->total_sold }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Enlaces rápidos -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white text-center font-bold py-6 px-4 rounded">
                    Añadir nuevo producto
                </a>
                
                <a href="{{ route('admin.orders') }}" class="bg-green-500 hover:bg-green-700 text-white text-center font-bold py-6 px-4 rounded">
                    Ver pedidos
                </a>
                
                <a href="{{ route('admin.users') }}" class="bg-purple-500 hover:bg-purple-700 text-white text-center font-bold py-6 px-4 rounded">
                    Gestionar usuarios
                </a>
            </div>
        </div>
    </div>
</x-app-layout>