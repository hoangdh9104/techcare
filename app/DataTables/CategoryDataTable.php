<?php

namespace App\DataTables;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('admin.category.edit', $query->id) . "' class='btn btn-primary'><i class='far fa-edit'></i></a>";

                // Form xóa truyền thống với CSRF, method DELETE và confirm
                // $deleteForm = "<form action='" . route('admin.category.destroy', $query->id) . "' method='POST' style='display:inline-block;' onsubmit='return confirm(\"Are you sure you want to delete this category?\");'>";
                // $deleteForm .= csrf_field();
                // $deleteForm .= method_field('DELETE');
                // $deleteForm .= "<button type='submit' class='btn btn-danger ml-2'><i class='far fa-trash-alt'></i></button>";
                // $deleteForm .= "</form>";

                return $editBtn;
            })
            ->addColumn('icon', function ($query) {
                return '<i style="font-size:35px" class="' . $query->icon . '"></i>';
            })
            ->addColumn('status', function ($query) {
                if ($query->status == 1) {
                    $button = '<label class="custom-switch mt-2">
                                <input type="checkbox" checked name="custom-switch-checkbox" data-id="' . $query->id . '" class="custom-switch-input change-status">
                                <span class="custom-switch-indicator"></span>
                            </label>';
                } else {
                    $button = '<label class="custom-switch mt-2">
                                <input type="checkbox" name="custom-switch-checkbox" data-id="' . $query->id . '" class="custom-switch-input change-status">
                                <span class="custom-switch-indicator"></span>
                            </label>';
                }
                return $button;
            })
            ->rawColumns(['icon', 'action', 'status'])
            ->setRowId('id');
    }

    public function query(Category $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('category-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
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

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('STT')->width(100),
            Column::make('icon')->title('Icon')->width(100),
            Column::make('name')->title('Tên'),
            Column::make('status')->title('Trạng thái')->width(100),
            Column::computed('action')->title('Hành động')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Category_' . date('YmdHis');
    }
}
