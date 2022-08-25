
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="host">@lang('admin.form.mail_name')</label>
            <input value="{{get_settings('mail_name')}}" type="text" name="name" class="form-control" id="name"
                   placeholder="@lang('admin.form.mail_name')">
        </div>
    </div>
    <div class="col-sm-6">

        <div class="form-group">
            <label for="host">@lang('admin.form.mail_from')</label>
            <input value="{{get_settings('mail_from')}}" type="email" name="from" class="form-control" id="from"
                   placeholder="@lang('admin.form.mail_from')">
        </div>
    </div>
    <div class="col-sm-6">

        <div class="form-group">
            <label for="host">@lang('admin.form.mail_host')</label>
            <input value="{{get_settings('mail_host')}}" type="text" name="host" class="form-control" id="host"
                   placeholder="@lang('admin.form.mail_host')">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="host">@lang('admin.form.mail_port')</label>
            <input value="{{get_settings('mail_port')}}" type="number" name="port" class="form-control" id="port"
                   placeholder="@lang('admin.form.mail_port')">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="host">@lang('admin.form.mail_username')</label>
            <input value="{{get_settings('mail_username')}}" type="text" name="username" class="form-control"
                   id="username"
                   placeholder="@lang('admin.form.mail_username')">
        </div>
    </div>
    <div class="col-sm-6">

        <div class="form-group">
            <label for="host">@lang('admin.form.mail_password')</label>
            <input value="{{get_settings('mail_password')}}" type="password" name="password" class="form-control"
                   id="password"
                   placeholder="@lang('admin.form.mail_password')">
        </div>
    </div>
    <div class="col-sm-12">

        <div class="form-group">
            <label for="encryption">@lang('admin.form.mail_encryption')</label>
            <select class="form-control" name="encryption" id="encryption">
                <option {{get_settings('mail_encryption')=='tls'?'selected':''}} value="tls">TLS</option>
                <option {{get_settings('mail_encryption')=='ssl'?'selected':''}} value="ssl">SSL</option>
            </select>
        </div>

    </div>
</div>






