<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenueExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected $year;
    protected $month;

    public function __construct($year, $month = null)
    {
        $this->year  = $year;
        $this->month = $month;
    }

    public function collection()
    {
        $query = Order::where('status', 'delivered')
            ->whereYear('created_at', $this->year);

        if ($this->month) {
            $query->whereMonth('created_at', $this->month);
        }

        return $query->with('items')
            ->latest()
            ->get()
            ->map(function ($order) {
                return [
                    'Mã đơn hàng'       => $order->order_code,
                    'Ngày đặt'          => $order->created_at->format('d/m/Y H:i'),
                    'Khách hàng'        => $order->shipping_name,
                    'Số điện thoại'     => $order->shipping_phone,
                    'Địa chỉ'           => $order->shipping_address,
                    'Thanh toán'        => [
                        'cod'     => 'Tiền mặt (COD)',
                        'banking' => 'Chuyển khoản',
                        'vnpay'   => 'VNPay',
                        'momo'    => 'Momo',
                    ][$order->payment_method] ?? $order->payment_method,
                    'Tổng tiền (đ)'     => $order->total_amount,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Mã đơn hàng',
            'Ngày đặt',
            'Khách hàng',
            'Số điện thoại',
            'Địa chỉ',
            'Phương thức TT',
            'Tổng tiền (đ)',
        ];
    }

    public function title(): string
    {
        $title = 'Doanh thu ' . $this->year;
        if ($this->month) {
            $title .= ' - Tháng ' . $this->month;
        }
        return $title;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '2E75B6']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 38,
            'B' => 18,
            'C' => 20,
            'D' => 15,
            'E' => 35,
            'F' => 18,
            'G' => 18,
        ];
    }
}