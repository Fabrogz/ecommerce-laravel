<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = auth()->user()->cart;
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('products.index')
                ->with('error', 'Tu carrito está vacío.');
        }
        
        return view('checkout.index', compact('cart'));
    }
    
    public function process(Request $request)
    {
        $cart = auth()->user()->cart;
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('products.index')
                ->with('error', 'Tu carrito está vacío.');
        }
        
        // Calcular totales
        $subtotal = 0;
        foreach ($cart->items as $item) {
            $subtotal += $item->product->price * $item->quantity;
        }
        
        $tax = $subtotal * 0.16; // 16% de impuesto
        $total = $subtotal + $tax;
        
        // Crear orden
        $order = Order::create([
            'user_id' => auth()->id(),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'status' => 'paid', // Simulamos que el pago se realizó correctamente
        ]);
        
        // Crear items de orden
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
            
            // Actualizar stock
            $product = $item->product;
            $product->stock -= $item->quantity;
            $product->save();
        }
        
        // Limpiar carrito
        $cart->items()->delete();
        
        return redirect()->route('checkout.success', $order);
    }
    
    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        
        return view('checkout.success', compact('order'));
    }
}
