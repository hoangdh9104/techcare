<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserOrderDataTable extends DataTable
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
                $showBtn = "<a href='" . route('user.orders.show', $query->id) . "' class='btn btn-primary'><i class='far fa-eye'></i></a>";

                return $showBtn;
            })
            ->addColumn('invoice_id', function ($query) {
                return $query->invocie_id;
            })
            ->addColumn('customer', function ($query) {
                return $query->user->name;
            })
            ->addColumn('amount', function ($query) {

                return number_format($query->amount, 0, ',', '.')  . $query->currency_icon;
            })
            ->addColumn('date', function ($query) {
                return \Carbon\Carbon::parse($query->created_at)->format('d/m/Y');
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
                        return "<span class='badge bg-info'>Đã đã gói</span>";
                    case 'shipped':
                        return "<span class='badge bg-info'>Đang vận chuyển</span>";
                        // case 'out_for_delivery':
                        //     return "<span class='badge bg-primary'>Đang giao hàng</span>";
                    case 'delivered':
                        return "<span class='badge bg-success'>Đã giao</span>";
                    case 'received':
                        return "<span class='badge bg-success'>Đã nhận</span>";
                    case 'canceled':
                        return "<span class='badge bg-danger'>Đã hủy</span>";
                    default:
                        return "<span class='badge bg-secondary'>Không xác định</span>";
                }
            })
            ->addColumn('product_qty', function ($query) {
                return $query->orderProducts->sum('qty'); // thay 'qty' bằng tên cột chứa số lượng nếu bạn đặt tên khác
            })
            ->rawColumns(['order_status', 'action', 'payment_status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */ public function query(Order $model): QueryBuilder
    {
        return $model->newQuery()->where('user_id', Auth::id())->orderBy('created_at', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('userorder-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1, 'desc') // 1 = Ngày đặt hàng
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

            // Column::make('id')
            //     ->title('STT')
            //     ->width(10), // Set fixed width for better alignment
            Column::make('invoice_id')
                ->title('Mã đơn hàng')
                ->width(150), // Set width for equal spacing
            // Column::make('customer')
            //     ->title('Khách hàng')
            //     ->width(200), // Adjust width for balance
            Column::make('date')
                ->title('Ngày đặt hàng')
                ->width(150),
            Column::make('amount')
                ->title('Số tiền')
                ->width(150),
            Column::make('order_status')
                ->title('Trạng thái đơn hàng')
                ->width(150),
            Column::make('payment_status')
                ->title('Trạng thái thanh toán')
                ->width(150),
            Column::make('payment_method')
                ->title('Phương thức thanh toán')
                ->width(150),
            Column::computed('action')

                ->exportable(false)
                ->printable(false)
                ->width(250) // Adjust width of action column
                ->addClass('text-center')
                ->title('Hành động'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'UserOrder_' . date('YmdHis');
    }
}
