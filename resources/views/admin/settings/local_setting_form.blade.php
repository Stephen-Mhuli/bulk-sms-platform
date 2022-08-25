
@php $local_setting = json_decode(get_settings('local_setting')); @endphp
        <div class="form-group">
            <div class="row">
                <div class="col-12">
                    <label>{{trans('admin.settings.language')}} </label>
                    <select name="language" class="form-control">
                        @foreach(get_available_languages() as $lang)
                            <option {{isset($local_setting->language) && $local_setting->language==$lang?'selected':''}} value="{{$lang}}">{{$lang}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-6">
                    <label>{{trans('admin.settings.date_time_format')}} </label>

                    <select name="date_time_format"
                            class="form-control">
                        <option
                            {{isset($local_setting->date_time_format) && $local_setting->date_time_format=='d m Y'?'selected':''}} value="d m Y">{{trans('30 12 2021')}}</option>
                        <option
                            {{isset($local_setting->date_time_format) && $local_setting->date_time_format=='m d Y'?'selected':''}} value="m d Y">{{trans('12 30 2021')}}</option>
                        <option
                            {{isset($local_setting->date_time_format) && $local_setting->date_time_format=='Y d m'?'selected':''}} value="Y d m">{{trans('2021 30 12')}}</option>
                        <option
                            {{isset($local_setting->date_time_format) && $local_setting->date_time_format=='Y m d'?'selected':''}} value="Y m d">{{trans('2021 12 30')}}</option>
                        <option
                            {{isset($local_setting->date_time_format) && $local_setting->date_time_format=='d_M,Y'?'selected':''}}  value="d_M,Y">{{trans('17 July,2021')}}</option>
                        <option
                            {{isset($local_setting->date_time_format) && $local_setting->date_time_format=='M_d,Y'?'selected':''}}  value="M_d,Y">{{trans('July 17,2021')}}</option>
                    </select>
                </div>
                <div class="col-6">
                    <label>{{trans('admin.settings.date_time_separator')}} </label>

                    <select name="date_time_separator"
                            class="form-control">
                        <option
                            {{isset($local_setting->date_time_separator) && $local_setting->date_time_separator=='-'?'selected':''}} value="-">{{trans('-')}}</option>
                        <option
                            {{isset($local_setting->date_time_separator) && $local_setting->date_time_separator=='/'?'selected':''}} value="/">{{trans('/')}}</option>
                    </select>
                </div>
            </div>

        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-6">
                    <label>{{trans('admin.settings.timezone')}} </label>

                    <select id="timezone" name="timezone"
                            class="form-control select2 select2-hidden-accessible" style="width: 100% !important;">

                        @foreach(getAllTimeZones() as $time)
                            <option
                                {{isset($local_setting->timezone) && $local_setting->timezone==$time['zone']?'selected':''}} value="{{$time['zone']}}">
                                ({{$time['GMT_difference']. ' ) '.$time['zone']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6">
                    <label>{{trans('admin.settings.decimal_point')}} </label>
                    <select name="decimal_format"
                            class="form-control">
                        <option
                            {{isset($local_setting->decimal_format) && $local_setting->decimal_format==','?'selected':''}} value=",">{{trans('Comma (,)')}}</option>
                        <option
                            {{isset($local_setting->decimal_format) && $local_setting->decimal_format=='.'?'selected':''}} value=".">{{trans('Dot (.)')}}</option>
                    </select>
                </div>
            </div>

        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-6">
                    <label>{{trans('admin.settings.currency_symbol')}} </label>

                    <input
                        value="{{isset($local_setting->currency_symbol) ?$local_setting->currency_symbol:''}}"
                        class="form-control" type="text"
                        name="currency_symbol">

                </div>
                <div class="col-6">
                    <label>{{trans('admin.settings.currency_code')}} </label>

                    <input
                        value="{{isset($local_setting->currency_code) ?$local_setting->currency_code:''}}"
                        class="form-control" type="text"
                        name="currency_code"
                        placeholder="Ex: usd or eur">
                    <a target="_blank" class="pull-right"
                       href="https://www.iban.com/currency-codes">{{trans('admin.settings.find_yours')}}</a>
                </div>
            </div>

        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-4">
                    <label>{{trans('admin.settings.currency_symbol_position')}} </label>
                    <select name="currency_symbol_position"
                            class="form-control">
                        <option
                            {{isset($local_setting->currency_symbol_position) && $local_setting->currency_symbol_position=='before'?'selected':''}} value="before">{{trans('admin.settings.before')}}</option>
                        <option
                            {{isset($local_setting->currency_symbol_position) && $local_setting->currency_symbol_position=='after'?'selected':''}} value="after">{{trans('admin.settings.after')}}</option>
                    </select>
                </div>

                <div class="col-4">
                    <label>{{trans('admin.settings.decimals')}} </label>

                    <input
                        value="{{isset($local_setting->decimals) ?$local_setting->decimals:'0'}}"
                        class="form-control" type="number"
                        name="decimals">

                </div>

                <div class="col-4">
                    <label>{{trans('admin.settings.thousand_separator')}} </label>
                    <select name="thousand_separator"
                            class="form-control">
                        <option
                            {{isset($local_setting->thousand_separator) && $local_setting->thousand_separator==','?'selected':''}} value=",">{{trans('Comma (,)')}}</option>
                        <option
                            {{isset($local_setting->thousand_separator) && $local_setting->thousand_separator=='.'?'selected':''}} value=".">{{trans('Dot (.)')}}</option>
                    </select>
                </div>

            </div>

        </div>


