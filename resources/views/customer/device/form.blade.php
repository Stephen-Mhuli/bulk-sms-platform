<div class="row">
    <div class="from-group col-lg-6 col-xl-6 col-md-6 col-sm-12">
        <label for="">{{trans('customer.device_name')}}</label>
        <input type="text" class="form-control" name="name" value="{{isset($device)?$device->name:(old('name')?old('name'):'')}}">
    </div>

    <div class="from-group col-lg-6 col-xl-6 col-md-6 col-sm-12">
        <label for="">{{trans('customer.device_model')}}</label>
        <input type="text" {{isset($device)?'readonly':''}} class="form-control" name="model" value="{{isset($device)?$device->model:(old('model')?old('model'):'')}}">
    </div>

    <div class="from-group mt-3 col-lg-4 col-xl-4 col-md-4 col-sm-12">
        <label for="">{{trans('customer.android_v')}}</label>
        <input type="text" class="form-control" {{isset($device)?'readonly':''}} name="android_version" value="{{isset($device)?$device->android_version:(old('android_version')?old('android_version'):'')}}">
    </div>

    <div class="from-group mt-3 col-lg-4 col-xl-4 col-md-4 col-sm-12">
        <label for="">{{trans('customer.app_v')}}</label>
        <input type="text" class="form-control" {{isset($device)?'readonly':''}} name="app_version" value="{{isset($device)?$device->app_version:(old('app_version')?old('app_version'):'')}}">
    </div>

    <div class="from-group mt-3 col-lg-4 col-xl-4 col-md-4 col-sm-12">
        <label for="">{{trans('customer.status')}}</label>
        <select name="status" class="form-control" id="">
            <option {{isset($device) && $device->status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
            <option {{isset($device) && $device->status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
        </select>
    </div>
</div>
