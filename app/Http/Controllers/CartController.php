<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        return view('cart.index', compact('cart'));
    }
    
    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        
        $cart = $this->getCart();
        
        // Verificar si el producto ya está en el carrito
        $cartItem = $cart->items()->where('product_id', $product->id)->first();
        
        if ($cartItem) {
            // Actualizar cantidad
            $cartItem->quantity += $validated['quantity'];
            $cartItem->save();
        } else {
            // Añadir nuevo item
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
            ]);
        }
        
        return redirect()->route('cart.index')
            ->with('success', 'Producto añadido al carrito.');
    }
    
    public function update(Request $request, CartItem $item)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        
        $item->update([
            'quantity' => $validated['quantity'],
        ]);
        
        return redirect()->route('cart.index')
            ->with('success', 'Carrito actualizado.');
    }
    
    public function remove(CartItem $item)
    {
        $item->delete();
        
        return redirect()->route('cart.index')
            ->with('success', 'Producto eliminado del carrito.');
    }
    
    private function getCart()
    {
        $user = auth()->user();
        
        // Buscar o crear carrito para el usuario
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        
        return $cart;
    }
}