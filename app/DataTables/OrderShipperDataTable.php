<?php

namespace App\DataTables;

use App\Models\OrderShipper;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrderShipperDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    // public function dataTable(QueryBuilder $query): EloquentDataTable
    // {
    //     return (new EloquentDataTable($query))
    //         ->addColumn('action_shipper', function ($order) {
    //             // Hiển thị nút thao tác dựa trên status
    //             if (in_array($order->status, ['pending', 'waiting'])) {
    //                 return '<a href="' . route('shipper.orders.pickup', $order->order_id) . '" class="btn btn-sm btn-primary">Nhận đơn</a>';
    //             } elseif ($order->status === 'in_delivery') {
    //                 return '<a href="' . route('shipper.orders.deliver', $order->order_id) . '" class="btn btn-sm btn-success">Giao hàng</a>';
    //             } else {
    //                 return '';
    //             }
    //         })
    //         ->rawColumns(['action_shipper']) // Cho phép HTML
    //         ->setRowId('id');
    // }
    public function dataTable(QueryBuilder $query): EloquentDataTable
{
    return (new EloquentDataTable($query))
        ->addColumn('action_shipper', function ($order) {
            return '<a href="' . route('shipper.orders.show', $order->order_id) . '" class="btn btn-sm btn-info">Xem</a>';
        })
        ->rawColumns(['action_shipper']) // Cho phép hiển thị HTML
        ->setRowId('id');
}



    /**
     * Get the query source of dataTable.
     */
    public function query(OrderShipper $model): QueryBuilder
    {
        return $model->newQuery()
            ->join('orders', 'orders.id', '=', 'order_shippers.order_id')
            ->select([
                'order_shippers.id',
                'order_shippers.order_id',  // Đảm bảo order_id có
                'orders.order_code',
                'order_shippers.status',
                'order_shippers.delivered_at',
                'order_shippers.created_at',
                'order_shippers.updated_at'
            ]);
    }


    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('ordershipper-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */ public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('order_code')->title('Mã đơn'),
            Column::make('status')->title('Trạng thái'),
            Column::make('delivered_at')->title('Giao lúc'),
            Column::make('created_at')->title('Tạo lúc'),
            Column::make('updated_at')->title('Cập nhật'),

            Column::computed('action_shipper')
                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center')
                ->title('Thao tác'),
        ];
    }


    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'OrderShipper_' . date('YmdHis');
    }
}
