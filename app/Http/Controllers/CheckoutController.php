<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Services\CheckoutService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class CheckoutController extends Controller
{
    public function index(): View
    {
        return view('checkout.index');
    }

    public function web(CheckoutRequest $request, CheckoutService $checkout): View
    {
        return view('checkout.index', [
            'hasil' => $this->kalkulasi($request, $checkout),
        ]);
    }

    public function api(CheckoutRequest $request, CheckoutService $checkout): JsonResponse
    {
        return response()->json([
            'data' => $this->kalkulasi($request, $checkout),
        ]);
    }

    /** @return array<string, int|bool> */
    private function kalkulasi(CheckoutRequest $request, CheckoutService $checkout): array
    {
        $data = $request->validated();

        return $checkout->hitung(
            (int) $data['harga'],
            (int) $data['jumlah'],
            (bool) ($data['member'] ?? false),
            (bool) ($data['ekspres'] ?? false),
        );
    }
}
