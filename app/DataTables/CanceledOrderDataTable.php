<?php

namespace App\DataTables;

use App\Models\Order;
use App\Models\PendingOrder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CanceledOrderDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $showBtn = "<a href='" . route('admin.order.show', $query->id) . "' class='btn btn-primary'><i class='far fa-eye'></i></a>";
                // $deleteBtn = "<a href='" . route('admin.order.destroy', $query->id) . "' class='btn btn-danger ml-2 mr-2 delete-item'><i class='far fa-trash-alt'></i></a>";


                // return $showBtn . $deleteBtn;
                return $showBtn;
            })
            ->addColumn('customer', function ($query) {
                return $query->user->name;
            })
            ->addColumn('amount', function ($query) {
                return  number_format($query->amount, 0, ',', '.') . $query->currency_icon;
            })
            ->addColumn('date', function ($query) {
                return date('d-M-Y', strtotime($query->created_at));
            })
            ->addColumn('payment_status', function ($query) {
                if ($query->payment_status === 0 && $query->order_status === 'canceled' && $query->payment_method !== 'COD') {
                    return "<span class='badge bg-info'>Đang chờ hoàn tiền</span>";
                } elseif ($query->payment_status === 2) {
                    return "<span class='badge bg-info'>Đã hoàn tiền</span>";
                } elseif ($query->payment_status === 1) {
                    return "<span class='badge bg-success'>Đã thanh toán</span>";
                } elseif ($query->payment_status === 0) {
                    return "<span class='badge bg-warning'>Chờ thanh toán</span>";
                }
            })
            ->addColumn('order_status', function ($query) {
                switch ($query->order_status) {
                    case 'pending':
                        return "<span class='badge bg-warning'>Chờ xử lý</span>";
                    case 'processed_and_ready_to_ship':
                        return "<span class='badge bg-info'>Đã xử lý - Sẵn sàng giao</span>";
                    case 'dropped_off':
                        return "<span class='badge bg-info'>Đã đóng gói</span>";
                    case 'shipped':
                        return "<span class='badge bg-info'>Đang vận chuyển</span>";
                    case 'out_for_delivery':
                        return "<span class='badge bg-primary'>Đang giao hàng</span>";
                    case 'delivered':
                        return "<span class='badge bg-success'>Đã giao</span>";
                    case 'canceled':
                        return "<span class='badge bg-danger'>Đã hủy</span>";
                    default:
                        return "<span class='badge bg-secondary'>Không xác định</span>";
                }
            })
            ->addColumn('product_qty', function ($query) {
                return $query->orderProducts->sum('qty');
            })

            ->rawColumns(['order_status', 'action', 'payment_status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->with(['user', 'orderProducts'])->where('order_status', 'canceled')->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('pendingorder-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(0)
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
     */
    public function getColumns(): array
    {
        return [
            // Column::make('id')->title('Mã đơn'),
            Column::make('invocie_id')->title('Mã hóa đơn'),
            Column::make('customer')->title('Khách hàng')->addClass('text-center'),
            // Column::make('date')->title('Ngày đặt'),
            Column::make('product_qty')->title('Số lượng sản phẩm')->addClass('text-center'),
            Column::make('amount')->title('Tổng tiền'),
            Column::make('order_status')->title('Trạng thái đơn hàng'),
            Column::make('payment_status')->title('Trạng thái thanh toán'),
            Column::make('payment_method')->title('Phương thức thanh toán'),
            Column::computed('action')
                ->title('Thao tác')

                ->exportable(false)
                ->printable(false)
                ->width(100)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'PendingOrder_' . date('YmdHis');
    }
}
