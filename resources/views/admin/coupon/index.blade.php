@extends('admin.layouts.master')

@section('content')
      <!-- Main Content -->
        <section class="section">
          <div class="section-header">
            <h1>Mã giảm giá</h1>
          </div>

          <div class="section-body">

            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Tất cả mã giảm giá</h4>
                    <div class="card-header-action">
                        <a href="{{route('admin.coupons.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Tạo mới</a>
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
        $(document).ready(function(){
            $('body').on('click', '.change-status', function(){
                let isChecked = $(this).is(':checked');
                let id = $(this).data('id');

                $.ajax({
                    url: "{{route('admin.coupons.change-status')}}",
                    method: 'PUT',
                    data: {
                        status: isChecked,
                        id: id
                    },
                    success: function(data){
                        toastr.success(data.message)
                    },
                    error: function(xhr, status, error){
                        console.log(error);
                    }
                })

            })
        })
        document.addEventListener('DOMContentLoaded', function () {
        // Lắng nghe sự kiện click vào nút xóa
        document.querySelectorAll('.delete-item').forEach(function (button) {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const url = this.getAttribute('href');

                Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: 'Bạn sẽ không thể hoàn tác hành động này!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Vâng, xóa nó!',
                    cancelButtonText: 'Hủy bỏ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Gửi yêu cầu xóa
                        window.location.href = url;
                    }
                });
            });
        });
    });
    </script>
    
@endpush
