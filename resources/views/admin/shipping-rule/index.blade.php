@extends('admin.layouts.master')
@section('content')
<section class="section">
    <div class="section-header">
      <h1>Shipping Rule</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>All Shipping Rule</h4>
              <div class="card-header-action">
                <a href="{{route('admin.shipping-rule.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Create new</a>
              </div>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('click', async function (event) {
                if (event.target.classList.contains('change-status')) {
                    const checkbox = event.target;
                    const isChecked = checkbox.checked;
                    const id = checkbox.getAttribute('data-id');
                    try {
                        const response = await fetch("{{route('admin.shipping-rule.change-status')}}", {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Đảm bảo thêm CSRF nếu Laravel yêu cầu
                            },
                            body: JSON.stringify({
                                status: isChecked,
                                id: id
                            })
                        });

                        const data = await response.json();

                        if (response.ok) {
                            toastr.success(data.message);
                        } else {
                            toastr.error(data.message || 'Có lỗi xảy ra!');
                        }
                    } catch (error) {
                        console.error('Lỗi:', error);
                        toastr.error('Có lỗi xảy ra trong quá trình xử lý!');
                    }
                }
            });
        });
    </script>
@endpush
