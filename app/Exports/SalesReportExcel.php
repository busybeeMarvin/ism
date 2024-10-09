<?php

namespace App\Exports;

use App\ProductDetail;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles as WithStylesAlias;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class SalesReportExcel implements FromQuery, WithHeadings, WithStylesAlias, WithColumnWidths, WithColumnFormatting
{
    public $start;
    public $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public function query()
    {

        return ProductDetail::query()
            ->selectRaw(
                '
        so.status,
        so.due_date,
        so.agent,
        so.so_no,
        c.name,
        product_details.product_name,
        product_details.qty,
        product_details.vendor_price,
        product_details.selling_price,
        CASE
            WHEN ROW_NUMBER() OVER (PARTITION BY so.so_no ORDER BY product_details.id) = 1
            THEN COALESCE(d.discount, 0)
            ELSE 0
        END as discount,
        CASE
            WHEN ROW_NUMBER() OVER (PARTITION BY so.so_no ORDER BY product_details.id) = 1
            THEN COALESCE(d.shipping, 0)
            ELSE 0
        END as summary_shipping,
        CASE
            WHEN ROW_NUMBER() OVER (PARTITION BY so.so_no ORDER BY product_details.id) = 1
            THEN COALESCE(d.sales_actual, 0)
            ELSE 0
        END as sales_actual,
        product_details.shipping as product_shipping,
        product_details.actual_sales,
        -- Calculate subtotal including (qty * selling_price)
        (
            (COALESCE(product_details.qty, 0) * COALESCE(product_details.selling_price, 0)) -
            COALESCE(
                CASE WHEN ROW_NUMBER() OVER (PARTITION BY so.so_no ORDER BY product_details.id) = 1
                THEN d.discount ELSE 0 END, 0)
            + COALESCE(
                CASE WHEN ROW_NUMBER() OVER (PARTITION BY so.so_no ORDER BY product_details.id) = 1
                THEN d.shipping ELSE 0 END, 0)
            + COALESCE(
                CASE WHEN ROW_NUMBER() OVER (PARTITION BY so.so_no ORDER BY product_details.id) = 1
                THEN d.sales_actual ELSE 0 END, 0)
        ) as subtotal, -- Subtotal: (qty * selling_price) - discount + shipping + sales_actual
        so.payment_status,
        so.payment_method,
        so.vat_type'
            )
            ->join('sales_orders as so', 'so.id', '=', 'product_details.sales_order_id')
            ->leftJoin('summaries as d', 'd.sales_order_id', '=', 'product_details.sales_order_id')
            ->leftJoin('customers as c', 'c.id', '=', 'so.customer_id')
            ->whereIn('so.status', ['Sales', 'Project'])
            ->when($this->start && $this->end, function ($q) {
                return $q->whereBetween('so.due_date', [$this->start, $this->end]);
            })
            ->orderBy('so.so_no', 'desc');


        // dd($sales);



        // return ProductDetail::query()
        // ->selectRaw('
        //     so.status,
        //     so.due_date,
        //     so.agent,
        //     so.so_no,
        //     c.name,
        //     product_name,
        //     qty,
        //     vendor_price,
        //     selling_price,
        //     discount,
        //     shipping,
        //     actual_sales,
        //    (qty * selling_price) + shipping + actual_sales - discount as subtotal,
        //     so.payment_status,
        //     so.payment_method,
        //     so.vat_type'
        // )
        // ->join('sales_orders as so', 'so.id', '=', 'product_details.sales_order_id')
        // // ->leftJoin('summaries as d', 'd.sales_order_id', '=', 'product_details.sales_order_id')
        // ->leftJoin('customers as c', 'c.id', '=', 'so.customer_id')
        // ->whereIn('so.status', ['Sales', 'Project'])
        // ->when($this->start && $this->end, function ($q) {
        //     return $q->whereBetween('due_date', [$this->start, $this->end]);
        // })
        // // ->whereNull('product_details.purchase_order_id') // Specify the table
        // ->orderBy('so.so_no', 'desc');

    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 15,
            'D' => 20,
            'E' => 20,
            'F' => 10,
            'G' => 12,
            'H' => 12,
            'J' => 15,
            'K' => 15,
            'L' => 15,
            'M' => 15,
            'N' => 15,
            'O' => 15,
            'P' => 15,
            'Q' => 15,
            'R' => 15,
        ];
    }

    public function headings(): array
    {
        return [
            'Status',
            'Date',
            'Agent',
            'SO No.',
            'Customer Name',
            'Item',
            'QTY',
            'Vendor Price',
            'Selling Price',
            'Discount',
            'Shipping',
            'Sales Tax',
            'Zero Column',
            'Zero Column',
            'Sub Total',
            'Payment Status',
            'Form Of Payment',
            'Vat Type',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
}
