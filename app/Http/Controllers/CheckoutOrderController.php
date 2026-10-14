<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\CheckoutOrder;
use App\Services\CheckoutOrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CheckoutOrderController extends Controller
{
    public function index(): View
    {
        return view('orders.index', [
            'orders' => CheckoutOrder::query()->latest('id')->paginate(10),
        ]);
    }

    public function storeWeb(CheckoutRequest $request, CheckoutOrderService $orderService): RedirectResponse
    {
        $order = $this->saveValidated($request, $orderService);

        return redirect()->route('orders.index')
            ->with('success', "Pesanan #{$order->id} berhasil disimpan.");
    }

    public function storeApi(CheckoutRequest $request, CheckoutOrderService $orderService): JsonResponse
    {
        $order = $this->saveValidated($request, $orderService);

        return response()->json([
            'message' => 'Pesanan berhasil disimpan.',
            'data' => $order,
        ], 201)->header('Location', route('api.orders.show', ['order' => $order->id]));
    }

    public function showApi(CheckoutOrder $order): JsonResponse
    {
        return response()->json(['data' => $order]);
    }

    private function saveValidated(CheckoutRequest $request, CheckoutOrderService $orderService): CheckoutOrder
    {
        $data = $request->validated();

        return $orderService->simpan(
            (int) $data['harga'],
            (int) $data['jumlah'],
            (bool) ($data['member'] ?? false),
            (bool) ($data['ekspres'] ?? false),
        );
    }
}
