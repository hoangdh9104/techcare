<?php

namespace App\DataTables;

use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SubCategoryDataTable extends DataTable
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
                $editBtn = "<a href='" . route('admin.sub-category.edit', $query->id) . "' class='btn btn-primary'><i class='far fa-edit'></i></a>";

                // $deleteForm = "<form action='" . route('admin.sub-category.destroy', $query->id) . "' method='POST' style='display:inline-block;' onsubmit='return confirm(\"Are you sure you want to delete this item?\");'>";
                // $deleteForm .= csrf_field();
                // $deleteForm .= method_field('DELETE');
                // $deleteForm .= "<button type='submit' class='btn btn-danger ml-2'><i class='far fa-trash-alt'></i></button>";
                // $deleteForm .= "</form>";

                return $editBtn;
            })
            ->addColumn('category', function ($query) {
                return $query->category ? $query->category->name : 'N/A';
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
            ->rawColumns(['action', 'status'])
            ->setRowId('id');
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(SubCategory $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('subcategory-table')
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
            Column::make('id')->title('STT'),
            Column::make('name')->title('Tên danh mục'),
            Column::make('slug')->title('Slug'),
            Column::make('category')->title('Danh mục cha'),
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
        return 'SubCategory_' . date('YmdHis');
    }
}
