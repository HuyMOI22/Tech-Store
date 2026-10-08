<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\VNPayService;
use Illuminate\Http\Request;

class VNPayController extends Controller
{
    public function create(Order $order, VNPayService $vnpay)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->payment_status == 'paid') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Đơn hàng này đã được thanh toán!');
        }

        $paymentUrl = $vnpay->createPaymentUrl($order);
        return redirect($paymentUrl);
    }

    public function return(Request $request, VNPayService $vnpay)
    {
        $data = $request->all();

        if (!$vnpay->verifyReturn($data)) {
            return redirect()->route('home')
                ->with('error', '❌ Chữ ký không hợp lệ!');
        }

        $order = Order::where('order_code', $data['vnp_TxnRef'])->first();

        if (!$order) {
            return redirect()->route('home')
                ->with('error', '❌ Không tìm thấy đơn hàng!');
        }

        if ($data['vnp_ResponseCode'] == '00') {
            $order->update([
                'payment_status' => 'paid',
                'status'         => 'confirmed',
            ]);
            return redirect()->route('orders.show', $order)
                ->with('success', '💳 Thanh toán VNPay thành công!');
        }

        return redirect()->route('orders.show', $order)
            ->with('error', '❌ Thanh toán thất bại! Vui lòng thử lại.');
    }
}