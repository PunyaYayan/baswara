<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseFormatSame;

class TransactionController extends Controller
{
    //mengambil data transaksi
    public function all(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        if ($id) {
            $transaction = Transaction::with(['items.product'])->find('id');

            if ($transaction) {
                return ResponseFormatter::success(
                    $transaction,
                    'Data transaksi berhasil diambil'
                );
            }
        } else {
            return ResponseFormatter::error([
                null,
                'Data transaksi tidak ada',
                404
            ]);
        }

        $transaction = Transaction::with(['items.product'])->where('users_id', Auth::user()->id);

        if ($status) {
            $transaction->where('status', $status);
        }

        return ResponseFormatter::success($transaction, 'Data list transaksi berhasil diambil');
    }

    public function checkout(Request $request)
    {
        
        $request->validate([
            'items' => 'required|array',
            // ask gpt, untuk cek apakah id produk ada di db

            'items.*.id' => 'exists:products,id',
            'total_price' => 'required',
            'status' => 'required|in:PENDING, SUCCESS, FAILED'
        ]);

            $transaction = Transaction::create([
                'users_id' => Auth::user()->id,
                'total_price' => '0',
                'status' => $request->status,
            ]);

        
        // perulangan untuk membuat items
        foreach ($request->items as $product) {
         try{
             TransactionItem::create([
                 'users_id' => Auth::user()->id,
                 'products_id' => $product['id'],
                 'transactions_id' => $transaction->id,
                 'quantity' => $product['quantity']
                ]);
        } catch (Exception $e) {
            error_log($e);
        }
        }


        return ResponseFormatter::success($transaction->load('items.product'), 'Transaksi berhasil');
    }
}