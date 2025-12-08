<?php

namespace App\Services;
use App\Models\User;
use App\Models\Transaction;
use App\Http\Controllers\Admin\TransactionController;
class WalletService
{
    public function getUserBalance($user_id)
    {
        $user = User::where('id', $user_id)->select('balance')->first();

        return $user ? $user->balance : 0;
    }

    public function updateBalance($amount, $deliveryBoyId, $action)
    {
        /**
         * action = add / deduct
         */

        $user = User::find($deliveryBoyId);

        if (!$user) {
            return false; // User not found
        }

        if ($action == "add") {
            $user->balance += $amount;
        } else {
            $user->balance -= $amount;
        }
        return $user->save();
    }

    public function updateCashReceived($amount, $deliveryBoyId, $action)
    {
        /**
         * action = add / deduct
         */

        $user = User::find($deliveryBoyId);
        if (!$user) {
            return false; // User not found
        }

        if ($action == "add") {
            $user->cash_received += $amount;
        } elseif ($action == "deduct") {
            $user->cash_received -= $amount;
        }
        return $user->save();
    }
    public function updateWalletBalance($operation, $user_id, $amount, $message = "Balance Debited", $order_item_id = "", $is_refund = 0, $transaction_type = 'wallet')
    {
        $user = User::find($user_id);

        if (!$user) {
            $response['error'] = true;
            $response['error_message'] = "User does not exist";
            $response['data'] = [];
            return $response;
        }

        if ($operation == 'debit' && $amount > $user->balance) {
            $response['error'] = true;
            $response['error_message'] = "Debited amount can't exceed the user balance!";
            $response['data'] = [];
            return $response;
        }

        if ($amount == 0) {
            $response['error'] = true;
            $response['error_message'] = "Amount can't be zero!";
            $response['data'] = [];
            return $response;
        }

        if ($user->balance >= 0) {
            $data = [
                'transaction_type' => $transaction_type,
                'user_id' => $user_id,
                'type' => $operation,
                'amount' => $amount,
                'message' => $message,
                'order_item_id' => $order_item_id,
                'is_refund' => $is_refund,
            ];

            $payment_data = Transaction::where('order_item_id', $order_item_id)->pluck('type')->first();

            if ($operation == 'debit') {
                $data['message'] = $message ?: 'Balance Debited';
                $data['type'] = 'debit';
                $data['status'] = 'success';
                $user->balance -= $amount;
            } else if ($operation == 'credit') {
                $data['message'] = $message ?? 'Balance Credited';
                $data['type'] = 'credit';
                $data['status'] = 'success';
                $data['order_id'] = $order_item_id;
                if ($payment_data != 'razorpay') {
                    $user->balance += $amount;
                }
            } else {
                $data['message'] = $message ?: 'Balance refunded';
                $data['type'] = 'refund';
                $data['status'] = 'success';
                $data['order_id'] = $order_item_id;
                if ($payment_data != 'razorpay') {
                    $user->balance += $amount;
                }
            }

            $user->save();

            $request = new \Illuminate\Http\Request($data);
            $transactionController = app(TransactionController::class);

            $transactionController->store($request);
            $response['error'] = false;
            $response['message'] = "Balance Update Successfully";
            $response['data'] = [];
        } else {
            $response['error'] = true;
            $response['error_message'] = ($user->balance != 0) ? "User's Wallet balance less than {$user->balance} can be used only" : "Doesn't have sufficient wallet balance to proceed further.";
            $response['data'] = [];
        }

        return $response;
    }
}