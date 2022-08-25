<div class="form-group">
    <label for="title">@lang('admin.form.title')</label>
    <input value="{{isset($plan)?$plan->title:old('title')}}" type="text" name="title" class="form-control" id="title"
           placeholder="@lang('admin.form.input.title')">
</div>

<div class="form-group d-none">
    <label for="limit">@lang('admin.form.sms_limit')</label>
    <input value="{{old('sms_limit')?old('sms_limit'):(isset($plan)?$plan->sms_limit:0)}}" type="number" name="sms_limit" class="form-control" id="sms_limit"
           placeholder="@lang('admin.form.input.enter_sms_limit')">
</div>

<div class="form-group">
    <label for="contact_limit">@lang('admin.form.contact_limit')</label>
    <input value="{{isset($plan)?$plan->contact_limit:old('contact_limit')}}" type="number" name="contact_limit" class="form-control" id="contact_limit"
           placeholder="@lang('admin.form.input.enter_contact_limit')">
</div>

<div class="form-group">
    <label for="contact_limit">@lang('admin.form.device_limit')</label>
    <input value="{{isset($plan)?$plan->device_limit:old('device_limit')}}" type="number" name="device_limit" class="form-control" id="device_limit"
           placeholder="@lang('admin.form.input.enter_device_limit')">
</div>

<div class="form-group">
    <label for="contact_limit">@lang('admin.form.daily_receive_limit')</label>
    <input value="{{isset($plan)?$plan->daily_receive_limit:old('daily_receive_limit')}}" type="number" name="daily_receive_limit" class="form-control" id="daily_receive_limit"
           placeholder="@lang('admin.form.input.enter_daily_receive_limit')">
</div>

<div class="form-group">
    <label for="contact_limit">@lang('admin.form.daily_send_limit')</label>
    <input value="{{isset($plan)?$plan->daily_send_limit:old('daily_send_limit')}}" type="number" name="daily_send_limit" class="form-control" id="daily_send_limit"
           placeholder="@lang('admin.form.input.enter_daily_send_limit')">
</div>

<div class="form-group">
    <label for="price">@lang('admin.form.price')</label>
    <input value="{{isset($plan)?$plan->price:old('price')}}" type="number" name="price" class="form-control" id="price"
           placeholder="@lang('admin.form.input.price')">
</div>

<div class="form-group">
    <label for="recurring_type">@lang('admin.form.recurring_type')</label>
    <select class="form-control" name="recurring_type" id="recurring_type">
        <option {{isset($plan) && $plan->recurring_type=='weekly'?'selected':(old('recurring_type')=='weekly'?'selected':'')}} value="weekly">Weekly</option>
        <option {{isset($plan) && $plan->recurring_type=='monthly'?'selected':(old('recurring_type')=='monthly'?'selected':'')}} value="monthly">Monthly</option>
        <option {{isset($plan) && $plan->recurring_type=='yearly'?'selected':(old('recurring_type')=='yearly'?'selected':'')}} value="yearly">Yearly</option>
    </select>
</div>

<div class="form-group">
    <label for="status">@lang('admin.form.status')</label>
    <select class="form-control" name="status" id="status">
        <option {{isset($plan) && $plan->status=='Active'?'selected':(old('status')=='Active'?'selected':'')}} value="active">Active</option>
        <option {{isset($plan) && $plan->status=='Inactive'?'selected':(old('status')=='Inactive'?'selected':'')}} value="inactive">Inactive</option>
    </select>
</div>
