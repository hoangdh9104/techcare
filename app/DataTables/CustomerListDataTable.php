<?php

namespace App\DataTables;

use App\Models\CustomerList;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CustomerListDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('status', function ($query) {
            // if ($query->status == 'active') {
            //     $button = '<label class="custom-switch mt-2">
            //                 <input type="checkbox" checked name="custom-switch-checkbox" data-id="' . $query->id . '" class="custom-switch-input change-status">
            //                 <span class="custom-switch-indicator"></span>
            //             </label>';
            // } else {
            //     $button = '<label class="custom-switch mt-2">
            //                 <input type="checkbox" name="custom-switch-checkbox" data-id="' . $query->id . '" class="custom-switch-input change-status">
            //                 <span class="custom-switch-indicator"></span>
            //             </label>';
            // }return $button;
            switch ($query->status) {
                case 'active':
                    return "<span class='badge bg-success'>Đang hoạt động</span>";
                case 'inactive':
                    return "<span class='badge bg-danger'>Ngừng hoạt động</span>";
                default:
                    return "<span class='badge bg-secondary'>Không xác định</span>";
            }
        })
        ->addColumn('action', function ($query) {
            $showBtn = "<a href='" . route('admin.customer.show', $query->id) . "' class='btn btn-primary'><i class='far fa-eye'></i></a>";
            return $showBtn;
        })
        ->rawColumns(['status','action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->where('role', 'user')->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('customerlist-table')
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
     */
    public function getColumns(): array
    {
        return [

            
            Column::make('id')->title('STT'),
            Column::make('name')->title('Khách hàng'),
            Column::make('email')->title('Email'),
            Column::make('status')->title('Trạng thái khách hàng'),
            Column::computed('action')
                ->title('Thao tác')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'CustomerList_' . date('YmdHis');
    }
}
