`<div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
   <div class="card border">
    <div class="card-body">
        <form action="{{route('admin.generale-setting-update')}} " method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Tên trang</label>
                <input type="text" class="form-control" name="site_name" value="{{@$generalSettings->site_name}}">
            </div>
            <div class="form-group">
                <label>Bố cục</label>
               <select name="layout" id="" class="form-control">
                <option {{@$generalSettings->layout == 'LTR' ? 'selected' : ''}}  value="LTR">LTR</option>
                <option {{@$generalSettings->layout == 'RTL' ? 'selected' : ''}}  value="RTL">RTL</option>
               </select>
            </div>
            <div class="form-group">
                <label>Email liên hệ</label>
                <input type="text" class="form-control" name="contact_email" value="{{@$generalSettings->contact_email}}">
            </div>
            <div class="form-group">
                <label>SĐT liên hệ</label>
                <input type="tel" class="form-control" name="contact_phone" value="{{@$generalSettings->contact_phone}}">
            </div>
            <div class="form-group">

                <label>Địa chỉ</label>
                <input type="text" class="form-control" name="contact_address" value="{{@$generalSettings->contact_address}}">
            </div>
            <div class="form-group">
                <label>Google Map</label>
                <input type="text" class="form-control" name="map" value="{{@$generalSettings->map}}">
            </div>
            <hr>
            <div class="form-group">

                <label>Tiền tệ</label>
                <select name="currency_name" id="" class="form-control select2">
                    <option value="">Select</option>
                    @foreach (config('settings.currecy_list') as $currency)
                    <option {{@$generalSettings->currency_name == $currency ? 'selected' : ''}} value="{{$currency}}">{{$currency}}</option>
                    @endforeach

                </select>
            </div>
            <div class="form-group">
                <label>Biểu tượng tiền tệ</label>
                <input type="text" class="form-control" name="currency_icon" value="{{@$generalSettings->currency_icon}}">
            </div>
            <div class="form-group">
                <label>Múi giờ</label>
                <select name="time_zone" id="" class="form-control select2">
                    <option value="">Select</option>
                    @foreach (config('settings.time_zone') as $key=> $timeZone)
                    <option {{@$generalSettings->time_zone ==$key ? 'selected' : ''}}  value="{{$key}}">{{$key}}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
       </div>
   </div>
  </div> 
