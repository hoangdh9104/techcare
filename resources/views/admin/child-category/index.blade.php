@extends('admin.layouts.master')

@section('content')
<!-- Main Content -->
<section class="section">
    <div class="section-header">
        <h1>Danh mục con</h1>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Tất cả danh mục con</h4>
                        <div class="card-header-action">
                            <a href="{{ route('admin.child-category.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tạo mới danh mục con
                            </a>
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

{{-- @push('scripts')
{{ $dataTable->scripts() }}
    <script>
        $(document).ready(function() {
            $('body').on('click', '.change-status', function() {
                let isChecked = $(this).is(':checked') ? 1 : 0;
                let id = $(this).data('id');

                $.ajax({
                    url: "{{ route('admin.child-category.change-status') }}",
                    type: "PUT",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        status: isChecked
                    },
                    success: function(data) {
                        toastr.success(data.message);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        toastr.error('Something went wrong!');
                    }
                });
            });
        });
    </script>
@endpush --}}

@push('scripts')
    {{ $dataTable->scripts() }}
    <script>
        $(document).ready(function() {
            $('body').on('click', '.change-status', function() {
                let isChecked = $(this).is(':checked') ? 1 : 0;
                let id = $(this).data('id');

                $.ajax({
                    url: "{{ route('admin.child-category.change-status') }}",
                    type: "PUT",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        status: isChecked
                    },
                    success: function(data) {
                        // Nếu response là plain text, hiển thị trực tiếp:
                        toastr.success(data.message);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        toastr.error('Something went wrong!');
                    }
                });
            });
        });
    </script>
@endpush


