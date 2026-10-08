<?php

namespace App\Services;

class VNPayService
{
    private $vnp_TmnCode    = '54V3ZCQB';
    private $vnp_HashSecret = '24A309RP22LLVW960CLTP5VCTC4WG9EE';
    private $vnp_Url        = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    private $vnp_ReturnUrl  = 'http://127.0.0.1:8000/vnpay/return';

    public function createPaymentUrl($order)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $inputData = [
            'vnp_Version'    => '2.1.0',
            'vnp_TmnCode'    => $this->vnp_TmnCode,
            'vnp_Amount'     => (int)($order->total_amount * 100),
            'vnp_Command'    => 'pay',
            'vnp_CreateDate' => date('YmdHis'),
            'vnp_CurrCode'   => 'VND',
            'vnp_IpAddr'     => request()->ip() ?: '127.0.0.1',
            'vnp_Locale'     => 'vn',
            'vnp_OrderInfo'  => 'Thanh toan don hang ' . $order->order_code,
            'vnp_OrderType'  => 'billpayment',
            'vnp_ReturnUrl'  => $this->vnp_ReturnUrl,
            'vnp_TxnRef'     => $order->order_code,
            'vnp_ExpireDate' => date('YmdHis', strtotime('+30 minutes')),
        ];

        ksort($inputData);

        $hashData   = http_build_query($inputData);
        $hmac       = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);
        $paymentUrl = $this->vnp_Url . '?' . $hashData . '&vnp_SecureHash=' . $hmac;

        return $paymentUrl;
    }

    public function verifyReturn($data)
    {
        $vnp_SecureHash = $data['vnp_SecureHash'] ?? '';

        $inputData = collect($data)
            ->except(['vnp_SecureHash', 'vnp_SecureHashType'])
            ->sortKeys()
            ->toArray();

        $hashData = http_build_query($inputData);
        $hmac     = hash_hmac('sha512', $hashData, $this->vnp_HashSecret);

        return hash_equals($hmac, $vnp_SecureHash);
    }
}