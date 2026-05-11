<?php

namespace App\Http\Controllers;

use App\Services\BackendService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $backend;

    public function __construct(BackendService $backend)
    {
        $this->backend = $backend;
    }

    public function momoDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10000'
        ]);

        return $this->createDeposit($request->amount, 'momo');
    }

    public function paypalDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $amountVnd = $request->amount * 25000;

        return $this->createDeposit($amountVnd, 'paypal');
    }

    private function createDeposit($amount, $method)
    {
        try {
            $response = $this->backend->post('wallet/deposit', [
                'amount' => $amount,
                'paymentMethod' => $method
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data['paymentUrl'])) {
                    return redirect()->away($data['paymentUrl']);
                }

                $message = $data['message'] ?? 'Deposit request created.';
                return redirect()->route('wallet.index')->with('success', $message);
            }

            $message = $response->json()['message'] ?? 'Deposit request failed.';
            return back()->with('error', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to create deposit: ' . $e->getMessage());
        }
    }
}
