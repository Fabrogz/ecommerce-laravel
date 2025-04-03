<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Imagen del producto -->
                        <div class="md:w-1/3">
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                alt="{{ $product->name }}" class="w-full rounded-lg shadow">
                            @else
                            <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-500">Sin imagen</span>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Información del producto -->
                        <div class="md:w-2/3">
                            <h1 class="text-3xl font-bold">{{ $product->name }}</h1>
                            <p class="text-gray-500 mt-2">{{ $product->category->name }}</p>
                            
                            <div class="mt-4">
                                <p class="text-2xl font-bold">${{ number_format($product->price, 2) }}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $product->stock > 0 ? $product->stock . ' en stock' : 'Agotado' }}
                                </p>
                            </div>
                            
                            <div class="mt-6">
                                <h3 class="text-lg font-semibold">Descripción</h3>
                                <p class="mt-2 text-gray-700">{{ $product->description }}</p>
                            </div>
                            
                            @if($product->tags->count() > 0)
                            <div class="mt-4">
                                <h3 class="text-lg font-semibold">Etiquetas</h3>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach($product->tags as $tag)
                                    <span class="px-3 py-1 bg-gray-200 rounded-full text-sm">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            @if($product->stock > 0)
                            <div class="mt-8">
                                @auth
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <div class="flex items-center gap-4">
                                        <div>
                                            <label for="quantity" class="block text-sm font-medium text-gray-700">Cantidad</label>
                                            <input type="number" name="quantity" id="quantity" min="1" max="{{ $product->stock }}" value="1" 
                                                class="mt-1 block w-24 rounded-md border-gray-300 shadow-sm">
                                        </div>
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Añadir al carrito
                                        </button>
                                    </div>
                                </form>
                                @else
                                <div class="bg-gray-100 p-4 rounded-lg">
                                    <p>Para comprar este producto, por favor <a href="{{ route('login') }}" class="text-blue-500 hover:underline">inicia sesión</a> 
                                    o <a href="{{ route('register') }}" class="text-blue-500 hover:underline">regístrate</a>.</p>
                                </div>
                                @endauth
                            </div>
                            @else
                            <div class="mt-8">
                                <button disabled class="bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded cursor-not-allowed">
                                    Producto agotado
                                </button>
                            </div