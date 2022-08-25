<div class="form-group">
    <label for="number">@lang('customer.number')</label>
    <div class="input-group mb-3">
        <div class="input-group-prepend d-none">
            <select {{isset($contact)?'disabled':''}} class="form-control" name="contact_dial_code" id="contact_dial_code">
                @foreach(getCountryCode() as $key=>$code)
                    <option {{isset($contact) && $contact->contact_dial_code=="+".$code['code']?'selected':''}} value="+{{$code['code']}}">+{{$code['code']}}</option>
                @endforeach
            </select>
        </div>
        <input {{isset($contact)?'readonly':''}} value="{{isset($contact)?$contact->number:old('number')}}" type="text" name="number" class="form-control" id="number"
               placeholder="Enter number">
    </div>


</div>

<div class="form-group">
    <label for="first_name">@lang('customer.first_name')</label>
    <input value="{{isset($contact) && $contact->first_name?$contact->first_name:old('first_name')}}" type="text" name="first_name" class="form-control" id="first_name"
           placeholder="@lang('customer.first_name')">
</div>

<div class="form-group">
    <label for="last_name">@lang('customer.last_name')</label>
    <input value="{{isset($contact) && $contact->last_name?$contact->last_name:old('last_name')}}" type="text" name="last_name" class="form-control" id="last_name"
           placeholder="@lang('customer.last_name')">
</div>

<div class="form-group">
    <label for="address">@lang('customer.address')</label>
    <input value="{{isset($contact) && $contact->address?$contact->address:old('address')}}" type="text" name="address" class="form-control" id="address"
           placeholder="@lang('customer.address')">
</div>

<div class="form-group">
    <label for="city">@lang('customer.city')</label>
    <input value="{{isset($contact) && $contact->city?$contact->city:old('city')}}" type="text" name="city" class="form-control" id="city"
           placeholder="@lang('customer.city')">
</div>

<div class="form-group">
    <label for="state">@lang('customer.state')</label>
    <input value="{{isset($contact) && $contact->state?$contact->state:old('state')}}" type="text" name="state" class="form-control" id="state"
           placeholder="@lang('customer.state')">
</div>

<div class="form-group">
    <label for="zip_code">@lang('customer.zip_code')</label>
    <input value="{{isset($contact) && $contact->zip_code?$contact->zip_code:old('zip_code')}}" type="text" name="zip_code" class="form-control" id="zip_code"
           placeholder="@lang('customer.zip_code')">
</div>
<div class="form-group">
    <label for="email">@lang('customer.email')</label>
    <input value="{{isset($contact) && $contact->email? $contact->email:old('email')}}" type="text" name="email" class="form-control" id="email"
           placeholder="@lang('customer.email')">
</div>

<div class="form-group">
    <label for="company">@lang('customer.company')</label>
    <input value="{{isset($contact) && $contact->company?$contact->company:old('company')}}" type="text" name="company" class="form-control" id="company"
           placeholder="@lang('customer.company')">
</div>

<div class="form-group">
    <label for="note">@lang('customer.note')</label>
    <textarea type="text" name="note" class="form-control" id="note" placeholder="@lang('customer.note')">{{isset($contact) && $contact->note?$contact->note:old('note')}}</textarea>
</div>
