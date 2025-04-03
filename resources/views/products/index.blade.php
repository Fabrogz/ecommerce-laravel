<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Productos') }}
            </h2>
            @can('create', App\Models\Product::class)
            <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Nuevo Producto
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('products.index') }}" method="GET" class="flex flex-wrap gap-4">
                        <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div class="w-48">
                            <label for="category" class="block text-sm font-medium text-gray-700">Categoría</label>
                            <select name="category" id="category" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Todas las categorías</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Productos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @forelse($products as $product)
                            <div class="bg-white rounded-lg border border-gray-200 shadow-md overflow-hidden">
                                @if($product->image)
<img src="{{ asset('storage/' . $product->image) }}" 
                                    alt="{{ $product->name }}" class="w-full h-48 object-cover">
                                @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500">Sin imagen</span>
                                </div>
                                @endif
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                                    <p class="text-gray-500">{{ $product->category->name }}</p>
                                    <p class="mt-2 text-xl font-bold">${{ number_format($product->price, 2) }}</p>
                                    <div class="mt-4 flex justify-between items-center">
                                        <a href="{{ route('products.show', $product) }}" 
                                            class="text-blue-500 hover:underline">Ver detalles</a>
                                        @can('update', $product)
                                        <a href="{{ route('products.edit', $product) }}" 
                                            class="text-gray-500 hover:text-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <p class="text-gray-500">No se encontraron productos</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>