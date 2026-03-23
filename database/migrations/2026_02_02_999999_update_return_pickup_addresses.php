<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\ProductReturn;
use App\Models\CustomerAddress;

return new class extends Migration
{
    public function up()
    {
        $returns = ProductReturn::whereNull('pickup_address')
            ->orWhere('pickup_address', '')
            ->get();

        foreach ($returns as $return) {
            $address = CustomerAddress::where('customer_id', $return->customer_id)
                ->where('is_default', true)
                ->first();

            if ($address) {
                $fullAddress = $address->address_line1 . ', ' . 
                    ($address->address_line2 ? $address->address_line2 . ', ' : '') .
                    $address->city . ', ' . 
                    $address->state . ' - ' . 
                    $address->pincode;

                $return->update(['pickup_address' => $fullAddress]);
            }
        }
    }

    public function down()
    {
        // Not reversible
    }
};
