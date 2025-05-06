<?php

namespace App\DataTables;

use App\Models\ChildCategory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ChildCategoryDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('admin.child-category.edit', $query->id) . "' class='btn btn-primary'><i class='far fa-edit'></i></a>";

                // Sử dụng form truyền thống để xóa với CSRF token, method DELETE và confirm
                // $deleteForm = "<form action='".route('admin.child-category.destroy', $query->id)."' method='POST' style='display:inline-block;' onsubmit='return confirm(\"Are you sure you want to delete this child category?\");'>";
                // $deleteForm .= csrf_field();
                // $deleteForm .= method_field('DELETE');
                // $deleteForm .= "<button type='submit' class='btn btn-danger ml-2'><i class='far fa-trash-alt'></i></button>";
                // $deleteForm .= "</form>";

                return $editBtn;
            })
            ->addColumn('status', function ($query) {
                if ($query->status == 1) {
                    return '<label class="custom-switch mt-2">
                        <input type="checkbox" checked name="custom-switch-checkbox" data-id="' . $query->id . '" class="custom-switch-input change-status">
                        <span class="custom-switch-indicator"></span>
                    </label>';
                } else {
                    return '<label class="custom-switch mt-2">
                        <input type="checkbox" name="custom-switch-checkbox" data-id="' . $query->id . '" class="custom-switch-input change-status">
                        <span class="custom-switch-indicator"></span>
                    </label>';
                }
            })
            ->addColumn('category', function ($query) {
                return $query->category ? $query->category->name : 'N/A';
            })
            ->addColumn('sub_category', function ($query) {
                return $query->subCategory ? $query->subCategory->name : 'N/A';
            })
            ->rawColumns(['status', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ChildCategory $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('childcategory-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
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
            Column::make('name')->title('Tên danh mục con'),
            Column::make('category')->title('Danh mục cha'),
            Column::make('sub_category')->title('Danh mục chi tiết'),
            Column::make('status')->title('Trạng thái'),
            Column::computed('action')->title('Hành động')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ChildCategory_' . date('YmdHis');
    }
}
