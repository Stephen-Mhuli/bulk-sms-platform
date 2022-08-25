<div class="form-group">
    <label for="first_name">{{trans('customer.first_name')}}</label>
    <input value="{{old('first_name')??$customer->first_name}}" type="text" name="first_name" class="form-control" id="first_name"
           placeholder="{{trans('customer.first_name')}}">
</div>
<div class="form-group">
    <label for="last_name">{{trans('customer.last_name')}}</label>
    <input value="{{old('last_name')??$customer->last_name}}" type="text" name="last_name" class="form-control" id="last_name"
           placeholder="{{trans('customer.last_name')}}">
</div>
<div class="form-group">
    <label for="email">{{trans('customer.email_address')}}</label>
    <input value="{{old('email')??$customer->email}}" type="email" name="email" class="form-control" id="email"
           placeholder="{{trans('customer.email_address')}}" autocomplete="off">
</div>
<div class="form-group">
    <label for="profile">{{trans('customer.profile_picture')}}</label>
    <div class="input-group">
        <div class="custom-file">
            <input name="profile" type="file" class="custom-file-input" id="profile">
            <label class="custom-file-label" for="profile">{{trans('customer.choose_file')}}</label>
        </div>
    </div>
</div>
