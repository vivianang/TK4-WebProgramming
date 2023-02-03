<?php

namespace App\Http\Controllers;

use App\DataTables\PendingTransactionDataTable;
use App\DataTables\TransactionDataTable;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    public function index(Request $request, TransactionDataTable $dataTable)
    {
        if ($request->user()->cannot('transaction:browse')) {
            return redirect()->route('dashboard');
        }

        return $dataTable->render('pages.transactions.index');
    }

    public function indexPending(Request $request, PendingTransactionDataTable $dataTable)
    {
        if ($request->user()->cannot('transaction:browse') || $request->user()->cannot('transaction:approve')) {
            return redirect()->route('dashboard');
        }

        return $dataTable->render('pages.transactions.index_pending');
    }

    public function add(Request $request)
    {
        if ($request->user()->cannot('transaction:add')) {
            return redirect()->route('dashboard');
        }

        $data = [];

        if ($request->user()->customer == null) {
            $data['customers'] = Customer::all();
        }
        $data['items'] = Item::all();

        return view('pages.transactions.add', $data);
    }

    public function submitAdd(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $transaction = Transaction::create([
                'customer_id' => $request->get('customer_id'),
                'total' => 0,
                'status' => 'PENDING'
            ]);

            $numberTotal = 0;

            foreach ($request->get('item_id') as $index => $value) {
                $qty = $request->get('qty')[$index];
                $item = Item::where('id', '=', $value)->first();
                if ($item == null) {
                    continue;
                }

                $total = $item->selling_price * $qty;
                $numberTotal += $total;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'item_id' => $item->id,
                    'qty' => $qty,
                    'price' => $item->selling_price,
                    'total_price' => $total
                ]);
            }

            $transaction->total = $numberTotal;
            $transaction->save();
            DB::commit();

            return redirect()->route('transactions.index');
        } catch (\PDOException $e) {
            dd($e);
            DB::rollBack();
        }

        return back();
    }

    public function view(Request $request, string $id)
    {
        if ($request->user()->cannot('transaction:read')) {
            return redirect()->route('dashboard');
        }

        $transactionData = Transaction::where('id', '=', $id)->first();
        if ($request->user()->customer != null && $transactionData->customer_id != $request->user()->customer->id) {
            return back();
        }

        return view('pages.transactions.view', [
            'data' => $transactionData,
        ]);
    }

    public function delete(Request $request, string $id)
    {
        if ($request->user()->cannot('transaction:delete')) {
            return redirect()->route('dashboard');
        }

        $transactionData = Transaction::where('id', '=', $id)->first();
        if ($request->user()->customer != null && $transactionData->customer_id != $request->user()->customer->id) {
            return back();
        }

        $transactionData->status = 'REVOKED';
        $transactionData->save();
        return redirect()->route('transactions.index');
    }

    public function approve(Request $request, string $id)
    {
        if ($request->user()->cannot('transaction:delete')) {
            return redirect()->route('dashboard');
        }

        $transactionData = Transaction::where('id', '=', $id)->first();
        
        try {
            DB::beginTransaction();

            foreach ($transactionData->details as $detail) {
                $detail->item->stock -= $detail->qty;
                $detail->item->save();
            }

            $transactionData->status = 'APPROVED';
            $transactionData->approved_at = new \DateTime();
            $transactionData->save();
            DB::commit();

            return redirect()->route('transactions.index');
        } catch (\PDOException $e) {
            dd($e);
            DB::rollBack();
        }

        return back();
    }
}
