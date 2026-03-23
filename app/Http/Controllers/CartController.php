<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        
        //for displaying images
        $placeholder = 'https://placehold.co/200?text=No+Image';
        foreach ($cartItems as $item) {
            $product = $item->product;
            if ($product->img_path) {
                $product->image = $product->img_path;
            } else {
                $product->image = $placeholder;
            }
        }
        return view('cart', compact('cartItems'));
    }

    public function increaseQty($id)
    {
        $cartItem = Cart::find($id);
        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        }
        return redirect()->back();
    }

    public function decreaseQty($id)
    {
        $cartItem = Cart::find($id);
        
        // If qty is 1, delete it. If more, just subtract 1.
        if ($cartItem) {
            if ($cartItem->quantity > 1) {
                $cartItem->quantity -= 1;
                $cartItem->save();
            } else {
                $cartItem->delete();
            }
        }
        return redirect()->back();
    }

    // 2. Add Item to Cart
    public function addToCart(Request $request)
    {
        // Check if product is already in cart
        $existingItem = Cart::where('product_id', $request->product_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingItem) {
            // If exists, just add +1 to quantity
            $existingItem->quantity += 1;
            $existingItem->save();
        } else {
            Cart::create([
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
                'quantity' => 1
            ]);
        }

        return redirect()->route('cart.index');
    }

    public function destroy($id)
    {
        Cart::destroy($id);
        return redirect()->route('cart.index');
    }
}