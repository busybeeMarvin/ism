<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class TopProductsLivewire extends Component
{
    public $tops;

    public $month;

    public $year;

    public function mount()
    {
        $this->month = now()->format('m');
        $this->year = now()->format('Y');
    }

    public function render()
    {
        $this->tops = DB::select("SELECT SUM(pd.qty * pd.selling_price) AS qty_total, pd.product_id, p.name
                FROM sales_orders
                LEFT JOIN product_details AS pd ON pd.sales_order_id = sales_orders.id
                LEFT JOIN products AS p ON p.id = pd.product_id
                WHERE MONTH(sales_orders.created_at) = {$this->month}
                AND YEAR(sales_orders.created_at) = {$this->year}
                AND sales_orders.status <> 'Quote'
                GROUP BY pd.product_id, p.name
                ORDER BY 1 desc
                LIMIT 10");

        return view('livewire.top-products-livewire');
    }
}
