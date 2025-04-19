<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('image', function ($query) {
                return "<img width='70px' src='" . asset($query->thumb_image) . "' ></img>";
            })
            ->addColumn('type', function ($query) {
                switch ($query->product_type) {
                    case 'new_arrival':
                        return '<i class="badge badge-success">Hàng mới về</i>';
                        break;
                    case 'featured_product':
                        return '<i class="badge badge-warning">Sản phẩm nổi bật</i>'; // Sản phẩm muốn quảng bá
                        break;
                    case 'top_product':
                        return '<i class="badge badge-info">Sản phẩm bán chạy</i>';
                        break;
                    case 'best_product':
                        return '<i class="badge badge-danger">Sản phẩm tốt nhất</i>';
                        break;
                    default:
                        return '<i class="badge badge-dark">Không có</i>';
                        break;
                }
            })
            ->addColumn('price_display', function ($query) {
                $hasVariants = $query->variants->count() > 0;
                if ($hasVariants) {
                    return '<span class="text-muted">Theo biến thể</span>';
                } else {
                    return number_format($query->price) . ' đ';
                }
            })
            ->addColumn('has_variants', function ($query) {
                $variantCount = $query->variantCombinations->count();
                if ($variantCount > 0) {
                    return '<span class="badge badge-primary"> ' . $variantCount . ' biến thể</span>';
                } else {
                    return '<span class="badge badge-light">Không có</span>';
                }
            })
            ->addColumn('status', function ($query) {
                if ($query->status == 1) {
                    $button = '<label class="custom-switch mt-2">
                        <input type="checkbox" checked name="custom-switch-checkbox" data-id="' . $query->id . '" class="custom-switch-input change-status" >
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
            ->addColumn('action', function ($query) {
                $showBtn = "<a href='" . route('admin.products.show', $query->id) . "' class='btn btn-info mr-1'><i class='far fa-eye'></i></a>";
                $editBtn = "<a href='" . route('admin.products.edit', $query->id) . "' class='btn btn-primary'><i class='far fa-edit'></i></a>";
                // $deleteBtn = "<a href='" . route('admin.products.destroy', $query->id) . "' class='btn btn-danger ml-2 delete-item'><i class='far fa-trash-alt'></i></a>";
                $moreBtn = '<div class="dropdown d-inline dropleft">
                <button class="btn btn-primary dropdown-toggle ml-1" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-cog"></i>
                </button>
                <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, -9px, 0px); top: 0px; left: 0px; will-change: transform;">
                <a class="dropdown-item has-icon" href="' . route('admin.products-image-gallery.index', ['product' => $query->id]) . '"><i class="far fa-heart"></i> Thư mục ảnh</a>
                <a class="dropdown-item has-icon" href="' . route('admin.products-variant.index', ['product' => $query->id]) . '"><i class="far fa-file"></i> Thuộc tính</a>
                </div>
                </div>';
                // return $editBtn . $deleteBtn . $moreBtn;
                return $showBtn . $editBtn . $moreBtn;
            })
            ->filterColumn('type', function ($query, $keyword) {
                $query->whereRaw('LOWER(product_type) LIKE ?', ['%' . strtolower(trim($keyword)) . '%']);
            })
            ->rawColumns(['image', 'type', 'status', 'action', 'has_variants', 'price_display'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->where('vendor_id', Auth::user()->vendor->id)->newQuery();
        // return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('product-table')
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
//     return [
//         Column::make('id')->title('STT'),
//         Column::make('image')->title('Hình ảnh'),
//         Column::make('name')->title('Tên sản phẩm'),
//         Column::make('price_display')->title('Giá')->addClass('text-right'),
//         Column::make('has_variants')->title('Biến thể')->width(100)->addClass('text-center'),
//         Column::make('type')->width(150)->title('Loại'),
//         Column::make('status')->title('Trạng thái'),
//         Column::computed('action')
//             ->exportable(false)
//             ->printable(false)
//             ->width(200)
//             ->addClass('text-center')
//             ->title('Thao tác'),
//     ];
// }

//     {
        return [
            Column::make('id')->title('STT'),
            Column::make('image')->title('Hình ảnh'),
            Column::make('name')->title('Tên sản phẩm'),
            // Column::make('price_display')->title('Giá')->addClass('text-right'),
            Column::make('has_variants')->title('Biến thể')->width(100)->addClass('text-center'),
            Column::make('type')->width(150)->title('Loại'),
            Column::make('status')->title('Trạng thái'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center')
                ->title('Thao tác'),
        ];
    }



    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}
