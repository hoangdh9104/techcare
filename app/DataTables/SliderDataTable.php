<?php

namespace App\DataTables;

use App\Models\Slider;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SliderDataTable extends DataTable
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
                $editBtn = "<a href='" . route('admin.slider.edit', $query->id) . "' class='btn btn-primary'><i class='far fa-edit'></i></a> ";
                $deleteBtn = "<a href='" . route('admin.slider.destroy', $query->id) . "' class='btn btn-danger ml-2 delete-item'><i class='fas fa-trash-alt'></i></a> ";
                return $editBtn . $deleteBtn;
            })
            ->addColumn('banner', function ($query) {
                // Sử dụng div với background-image thay vì thẻ img
                return '<div style="width: 100px; height: 50px; background-image: url(\'' . asset($query->banner) . '\'); background-size: cover; background-position: center;"></div>'; // Điều chỉnh height: 50px cho phù hợp
            })
            ->addColumn('status', function ($query) {
                $active = '<i class="badge badge-success">Hoạt động</i>';
                $inActive = '<i class="badge badge-danger">Dừng hoạt động</i>';
                if ($query->status == 1) {
                    return $active;
                } else {
                    return $inActive;
                }
            })
            ->rawColumns(['banner', 'action', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Slider $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('slider-table')
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

            Column::make('id')->title('STT')->width(100),
            Column::make('banner')->title('Ảnh banner')->width(200),
            Column::make('title')->title('Tiêu đề'),
            Column::make('serial')->title('Thứ tự'),
            Column::make('status')->title('Trạng thái'),
            Column::computed('action')->title('Hành động')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center'),
            // Column::make('created_at'),
            // Column::make('updated_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Slider_' . date('YmdHis');
    }
}
