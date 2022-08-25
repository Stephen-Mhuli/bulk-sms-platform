<div class="form-group">
    <label for="name">@lang('admin.form.input.app_name')</label>
    <input value="{{get_settings('app_name')}}" type="text" name="app_name" class="form-control" id="app_name"
           placeholder="@lang('admin.form.input.app_name')">
</div>

<div class="form-group">
    <label for="recaptcha_site_key">@lang('admin.form.input.recaptcha_site_key')
        <span class="ml-2" data-toggle="tooltip" data-placement="right" title="@lang('admin.recaptcha_description')">
            <i class="fa fa-question-circle"></i>
        </span>
    </label>
    <input value="{{get_settings('recaptcha_site_key')}}" type="text" name="recaptcha_site_key" class="form-control" id="recaptcha_site_key"
           placeholder="@lang('admin.form.input.ex_recaptcha_site_key')">
</div>
<div class="form-group">
    <label for="link_apk">@lang('admin.form.input.link_for_apk')
        <span class="ml-2" data-toggle="tooltip" data-placement="right" title="@lang('admin.apk_download_description')">
            <i class="fa fa-question-circle"></i>
        </span>
    </label>
    <input value="{{get_settings('link_apk')}}" type="text" name="link_apk" class="form-control" id="link_apk"
           placeholder="@lang('admin.form.input.ex_link_for_apk')">
</div>

<div class="form-group">
    <label for="favicon">@lang('admin.form.input.favicon')</label><img class="img-demo-setting" src="{{asset('uploads/'.get_settings('app_favicon'))}}" alt="">
    <div class="input-group">
        <div class="custom-file">
            <input name="favicon" type="file" class="custom-file-input" id="favicon">
            <label class="custom-file-label" for="favicon">@lang('admin.form.input.choose_file')</label>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="logo">@lang('admin.form.input.logo')</label> <img class="img-demo-setting" src="{{asset('uploads/'.get_settings('app_logo'))}}" alt="">
    <div class="input-group">
        <div class="custom-file">
            <input name="logo" type="file" class="custom-file-input" id="logo">
            <label class="custom-file-label" for="logo">@lang('admin.form.input.choose_file')</label>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="status">@lang('admin.addon.customer_registration')
        <span class="ml-2" data-toggle="tooltip" data-placement="right" title="@lang('admin.registration_enabling_disabling_description')">
            <i class="fa fa-question-circle"></i>
        </span>
    </label>
    <select class="form-control" name="registration_status" id="registration_status">
        <option {{get_settings('registration_status')=='disable'?'selected':''}} value="disable">Disable</option>
        <option {{get_settings('registration_status')=='enable'?'selected':''}} value="enable">Enable</option>
    </select>
</div>

@if(isset($_SERVER['HTTPS']))
<div class="form-group">
    <label for="status">@lang('Cron Job URL')
        <span class="ml-2" data-toggle="tooltip" data-placement="right" title="Add the url cron job or hit the url manually by other browser">
            <i class="fa fa-question-circle"></i>
        </span>
    </label>
    <input type="text" readonly value="{{$_SERVER['HTTPS'].'/check/schedule'}}" class="form-control">
</div>
@endif

