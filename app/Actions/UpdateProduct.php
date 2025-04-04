<?php

namespace App\Actions;

use App\Jobs\SendPriceChangeNotification;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class UpdateProduct
{
    public function handle(Request $request, $id)
    {
        $product = Product::find($id);

        // Store the old price before updating
        $oldPrice = $product->price;

        $product->update($request->all());

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $product->image = 'uploads/' . $filename;
        }

        $product->save();

        // Check if price has changed
        if ($oldPrice != $product->price) {
            // Get notification email from env
            $notificationEmail = env('PRICE_NOTIFICATION_EMAIL', 'admin@example.com');

            try {
                SendPriceChangeNotification::dispatch(
                    $product,
                    $oldPrice,
                    $product->price,
                    $notificationEmail
                );
            } catch (\Exception $e) {
                Log::error('Failed to dispatch price change notification: ' . $e->getMessage());
            }
        }
    }
}
