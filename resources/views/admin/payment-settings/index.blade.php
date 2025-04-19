@extends('admin.layouts.master')
@section('content')
<section class="section">
    <div class="section-header">
      <h1>Cài đặt phương thức thanh toán</h1>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-2">
                      <div class="list-group" id="list-tab" role="tablist">

                        <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list" href="#list-home" role="tab">Momo</a>
                        <a class="list-group-item list-group-item-action" id="list-vnpay-list" data-toggle="list" href="#list-vnpay" role="tab">VNpay</a>
                        <a class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab">COD</a>
                      </div>
                    </div>
                    <div class="col-8">
                      <div class="tab-content" id="nav-tabContent">

                        @include('admin.payment-settings.sections.momo-setting')

                        @include('admin.payment-settings.sections.vnpay-setting')

                        @include('admin.payment-settings.sections.cod-setting')

                      </div>
                    </div>
                  </div>
                </div>
              </div>
        </div>
      </div>
    </div>
</section>
@endsection

