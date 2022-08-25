<div class="form-group">
    <label for="word">@lang('customer.title') *</label>
    <input {{isset($label) && $label->title=='new'?'readonly':''}} value="{{isset($label) && $label->title?$label->title:old('title')}}" type="text" name="title"
           class="form-control" id="word"
           placeholder="@lang('Label Title')">
</div>

<div class="row">
    <div class="form-group col-sm-6">
        <label for="opt_type">{{trans('customer.status')}}</label>
        <select name="status" class="form-control" id="">
            <option {{isset($label) && $label->status=='active'?'selected':''}} value="active">{{trans('customer.active')}}</option>
            <option {{isset($label) && $label->status=='inactive'?'selected':''}} value="inactive">{{trans('customer.inactive')}}</option>
        </select>

    </div>
    <div class="col-sm-6">
        <label for="">Color  @if(isset($label) && isset($label->color)) <button class="btn disabled" disabled="disabled" style="padding: 0px 20px; background: {{$label->color}}">&nbsp;&nbsp;&nbsp;</button> @endif</label>
        <input type="color" class="form-control" value="{{isset($label) && isset($label->color)?$label->color:''}}" name="color">

    </div>
</div>

