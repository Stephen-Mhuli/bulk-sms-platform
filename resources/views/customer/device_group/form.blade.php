<div class="form-group">
    <label for="name">@lang('customer.group_name') *</label>
    <input value="{{isset($group) && $group->name?$group->name:old('name')}}" type="text" name="name"
           class="form-control" id="name"
           placeholder="@lang('customer.group_name')">
</div>
<div class="form-group">
    <label for="">{{trans('customer.select_devices')}}</label>
    <select name="device_name[]" class="form-control select2">
        @foreach($devices as $key=>$device)
             <option  value="{{$device->name}}">{{$device->name}}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="status">@lang('customer.status')</label>
    <select name="status" id="status" class="form-control">
        <option {{isset($group) && $group->status=='active'?'selected':''}} value="active">Active</option>
        <option {{isset($group) && $group->status=='inactive'?'selected':''}} value="inactive">Inactive</option>
    </select>
</div>
