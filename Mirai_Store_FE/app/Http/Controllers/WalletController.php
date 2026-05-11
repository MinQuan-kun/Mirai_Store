<?php

namespace App\Http\Controllers;

use App\Services\BackendService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class WalletController extends Controller
{
    protected $backend;

    public function __construct(BackendService $backend)
    {
        $this->backend = $backend;
    }

    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        try {
            $balanceResponse = $this->backend->get('wallet/balance');
            $transactionsResponse = $this->backend->get('wallet/transactions', [
                'filter' => $filter
            ]);

            $balance = 0;
            if ($balanceResponse->successful()) {
                $balanceData = $balanceResponse->json();
                $balance = $balanceData['balance'] ?? $balanceData['Balance'] ?? 0;
                Session::put('user_balance', $balance);
            }

            $transactions = collect();
            if ($transactionsResponse->successful()) {
                $transactionData = $transactionsResponse->json();
                $items = $transactionData['data'] ?? $transactionData['Data'] ?? [];
                $transactions = collect($items)->map(fn($t) => (object)[
                    'id' => $t['id'] ?? $t['Id'] ?? null,
                    'type' => $t['type'] ?? $t['Type'] ?? null,
                    'amount' => $t['amount'] ?? $t['Amount'] ?? 0,
                    'status' => $t['status'] ?? $t['Status'] ?? null,
                    'description' => $t['description'] ?? $t['Description'] ?? null,
                    'payment_method' => $t['paymentMethod'] ?? $t['PaymentMethod'] ?? null,
                    'order_id' => $t['orderId'] ?? $t['OrderId'] ?? null,
                    'created_at' => Carbon::parse($t['createdAt'] ?? $t['CreatedAt'] ?? now()),
                ]);
            }

            $currentPage = $request->get('page', 1);
            $perPage = 10;
            $pagedTransactions = new LengthAwarePaginator(
                $transactions->forPage($currentPage, $perPage),
                $transactions->count(),
                $perPage,
                $currentPage,
                ['path' => url()->current(), 'query' => $request->query()]
            );

            $user = (object)['balance' => $balance];

            return view('wallet.index', [
                'user' => $user,
                'transactions' => $pagedTransactions
            ]);
        } catch (\Exception $e) {
            $user = (object)['balance' => 0];
            return view('wallet.index', [
                'user' => $user,
                'transactions' => new LengthAwarePaginator([], 0, 10)
            ])->with('error', 'Unable to load wallet data: ' . $e->getMessage());
        }
    }

    public function showDeposit()
    {
        return view('wallet.deposit');
    }

    public function depositTest(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000'
        ]);

        try {
            $response = $this->backend->post('wallet/deposit', [
                'amount' => $request->amount,
                'paymentMethod' => 'test_card'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $message = $data['message'] ?? 'Deposit completed.';
                return redirect()->route('wallet.index')->with('success', $message);
            }

            $message = $response->json()['message'] ?? 'Deposit failed.';
            return back()->with('error', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to process deposit: ' . $e->getMessage());
        }
    }

    public function cancel($id)
    {
        try {
            $response = $this->backend->post("wallet/cancel/{$id}");

            if ($response->successful()) {
                $data = $response->json();
                $message = $data['message'] ?? 'Transaction canceled.';
                return back()->with('success', $message);
            }

            $message = $response->json()['message'] ?? 'Unable to cancel transaction.';
            return back()->with('error', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to cancel transaction: ' . $e->getMessage());
        }
    }
}
