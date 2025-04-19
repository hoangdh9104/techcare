<div class="tab-pane fade show" id="list-vnpay" role="tabpanel" aria-labelledby="list-vnpay-list">
    <div class="card border">
        <div class="card-body">
            <form action="{{ route('admin.vnpay-setting.update', 1) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Trạng thái VNpay</label>
                    <select name="status" class="form-control">
                        <option value="1" {{ $vnpaySetting->status == 1 ? 'selected' : '' }}>Kích hoạt</option>
                        <option value="0" {{ $vnpaySetting->status == 0 ? 'selected' : '' }}>Không kích hoạt</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Trạng thái tài khoản</label>
                    <select name="mode" class="form-control">
                        <option value="0" {{ $vnpaySetting->mode == 0 ? 'selected' : '' }}>Sandbox</option>
                        <option value="1" {{ $vnpaySetting->mode == 1 ? 'selected' : '' }}>Live</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>VNPay TmnCode</label>
                    <input type="text" class="form-control" name="tmn_code" value="{{ $vnpaySetting->tmn_code }}">
                </div>

                <div class="form-group">
                    <label>VNPay Hash Secret</label>
                    <input type="text" class="form-control" name="hash_secret" value="{{ $vnpaySetting->hash_secret }}">
                </div>

                <div class="form-group">
                    <label>VNPay Payment URL</label>
                    <input type="text" class="form-control" name="payment_url" value="{{ $vnpaySetting->payment_url }}">
                </div>

                <div class="form-group">
                    <label>VNPay Return URL</label>
                    <input type="text" class="form-control" name="return_url" value="{{ $vnpaySetting->return_url }}">
                </div>


                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div>
