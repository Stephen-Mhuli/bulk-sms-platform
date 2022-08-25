<div class="form-group">
    <label for="u_name">@lang('admin.form.input.name')</label>
    <input value="{{old('u_name')??isset($admin)?$admin->name:''}}" type="text" name="u_name" class="form-control" id="u_name"
           placeholder="@lang('admin.form.input.name')">
</div>
<div class="form-group">
    <label for="email">@lang('admin.form.input.email')
        <span class="ml-2" data-toggle="tooltip" data-placement="right" title="@lang('admin.profile_email_description')">
            <i class="fa fa-question-circle"></i>
        </span>
    </label>
    <input value="{{old('email')??isset($admin)?$admin->email:''}}" type="email" name="email" class="form-control" id="email"
           placeholder="@lang('admin.form.input.email')" autocomplete="off" readonly
           onfocus="remove_readonly(this)">
</div>
<div class="form-group">
    <label for="password">@lang('admin.form.input.password')
        <span class="ml-2" data-toggle="tooltip" data-placement="right" title="@lang('admin.profile_password_description')">
            <i class="fa fa-question-circle"></i>
        </span>
    </label>
    <input type="password" name="password" class="form-control" id="u_password"
           placeholder="@lang('admin.form.input.password')" autocomplete="off" readonly
           onfocus="remove_readonly(this)">
</div>
<div class="form-group">
    <label for="profile">@lang('admin.form.input.profile_picture')</label>
    <div class="input-group">
        <div class="custom-file">
            <input name="profile" type="file" class="custom-file-input" id="profile">
            <label class="custom-file-label" for="profile">@lang('admin.form.input.choose_file')</label>
        </div>
    </div>
</div>

