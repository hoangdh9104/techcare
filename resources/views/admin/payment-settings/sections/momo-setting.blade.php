<div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
    <div class="card border">
        <div class="card-body">
            <form action="{{ route('admin.momo-setting.update', 1) }} " method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Trạng thái Momo</label>
                    <select name="status" id="" class="form-control">
                        <option {{ $momoSetting->status === 1 ? 'selected' : '' }} value="1">Kích hoạt</option>
                        <option {{ $momoSetting->status === 0 ? 'selected' : '' }} value="0">Không kích hoạt</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Trạng thái tài khoản</label>
                    <select name="mode" id="" class="form-control">
                        <option {{ $momoSetting->mode === 0 ? 'selected' : '' }} value="0">Sanbox</option>
                        <option {{ $momoSetting->mode === 1 ? 'selected' : '' }} value="1">Live</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Quốc gia</label>
                    <select name="country_name" id="" class="form-control select2">
                        <option value="">Select</option>
                        @foreach (config('settings.country_list') as $country)
                            <option {{ $country === $momoSetting->country_name ? 'selected' : '' }}
                                value="{{ $country }}">{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Tiền tệ</label>
                    <select name="currency_name" id="" class="form-control select2">
                        <option value="">Select</option>
                        @foreach (config('settings.currecy_list') as $key => $currency)
                            <option {{$currency === $momoSetting->currency_name ? 'selected' : ''}} value="{{$currency}}">{{$key}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Tỉ giá ( Per {{ $settings->currency_name }} )</label>
                    <input type="text" class="form-control" name="currency_rate"
                        value="{{ $momoSetting->currency_rate }}">
                </div>
                <div class="form-group">
                    <label>Momo Parter Code</label>
                    <input type="text" class="form-control" name="partner_code" value="{{ $momoSetting->partner_code }}">
                </div>
                <div class="form-group">
                    <label>Momo Access Key</label>
                    <input type="text" class="form-control" name="access_key" value="{{ $momoSetting->access_key }}">
                </div>
                <div class="form-group">
                    <label>Momo Secret Key</label>
                    <input type="text" class="form-control" name="secret_key" value="{{ $momoSetting->secret_key }}">
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div> 
