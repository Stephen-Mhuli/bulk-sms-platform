<div class="form-group">
    <label for="first_name">@lang('admin.form.first_name')</label>
    <input value="{{isset($customer)?$customer->first_name:old('first_name')}}" type="text" name="first_name" class="form-control" id="first_name"
           placeholder="@lang('admin.form.input.first_name')">
</div>
<div class="form-group">
    <label for="last_name">@lang('admin.form.last_name')</label>
    <input value="{{isset($customer)?$customer->last_name:old('last_name')}}" type="text" name="last_name" class="form-control" id="last_name"
           placeholder="@lang('admin.form.input.last_name')">
</div>
<div class="form-group">
    <label for="email">@lang('admin.form.email')</label>
    <input value="{{isset($customer)?$customer->email:old('email')}}" type="email" name="email" class="form-control" id="email"
           placeholder="@lang('admin.form.input.email')" autocomplete="off" readonly
           onfocus="remove_readonly(this)">
</div>
<div class="form-group">
    <label for="password">@lang('admin.form.password')</label>
    <input type="password" name="password" class="form-control" id="password"
           placeholder="@lang('admin.form.input.password')">
</div>
<div class="form-group">
    <label for="status">@lang('admin.form.status')</label>
    <select class="form-control" name="status" id="status">
        <option {{isset($customer) && $customer->status=='Active'?'selected':(old('status')=='active'?'selected':'')}} value="active">Active</option>
        <option {{isset($customer) && $customer->status=='Inactive'?'selected':(old('status')=='inactive'?'selected':'')}} value="inactive">Inactive</option>
    </select>
</div>
