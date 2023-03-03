<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TopSalesAgentLivewire extends Component
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
        $this->tops = DB::select("SELECT SUM(s.grand_total) AS sales, agent as name
            FROM sales_orders
            LEFT JOIN users AS u ON u.id = assigned_to
            LEFT JOIN summaries AS s ON s.sales_order_id = sales_orders.id
                WHERE MONTH(sales_orders.created_at) = {$this->month}
                AND YEAR(sales_orders.created_at) = {$this->year}
                AND sales_orders.status = 'Sales'
                GROUP BY agent
                ORDER BY 1 desc
            LIMIT 3");
            
        return view('livewire.top-sales-agent-livewire');
    }
}
