<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <button type="button" class="btn active campaign_side_bar" data-type="bulk_send">Bulk Send Details</button>
        </div>
        <div class="form-group">
            <button type="button" class="btn campaign_side_bar" data-type="content">Set Your Content</button>
        </div>
        <div class="form-group">
            <button type="button" class="btn campaign_side_bar" data-type="resource">Set Your Resource</button>
        </div>
        <div class="form-group d-none">
            <button type="button" class="btn campaign_side_bar" data-type="rate">Set Your Rate</button>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="campaign_section" id="bulk_send_section">
            <h3>Enter Send Details</h3>
            <hr>
            <div class="form-group mt-3">
                <label for="">Campaign Title</label>
                <input type="text" class="form-control" value="{{old('title')??(isset($campaign)?$campaign->title:'')}}" required placeholder="Enter Campaign Title" name="title">
            </div>

            <div class="form-group from-number-section pb-3" id="select_campaign_devices">
                <label for="">From Devices :</label>
                <select name="from_devices[]" id="campaignFromDevices" class="select2 compose-select"
                        multiple="multiple"
                        data-placeholder="{{trans('customer.device')}}:">
                        @foreach($users_from_devices as $device)
                            <option value="{{$device->id}}">{{$device->name}} ({{$device->model}})</option>
                        @endforeach
                </select>
            </div>

            <div class="row">
                <div class="form-group col-sm-6 col-6">
                    <label for="">Start Date</label>
                    <input name="start_date" value="{{old('start_date')??(isset($campaign)??$campaign->start_date)}}" type='text' class="form-control date_range"/>
                </div>
                <div class="form-group col-sm-6 col-6">
                    <label for="">End Date</label>
                    <input name="end_date" value="{{old('end_date')??(isset($campaign)??$campaign->end_date)}}" type='text' class="form-control date_range"/>
                </div>

                <div class="form-group col-sm-6 col-6 mt-2">
                    <label for="">Start Time</label>
                    <input name="start_time" value="{{old('start_time')??(isset($campaign)?$campaign->start_time:'')}}" type='time' class="form-control"/>
                </div>
                <div class="form-group col-sm-6 col-6 mt-2">
                    <label for="">End Time</label>
                    <input name="end_time" value="{{old('end_time')??(isset($campaign)?$campaign->end_time:'')}}" type='time' class="form-control"/>
                </div>

            </div>
        </div>
        <input type="hidden" id="template_active_nav">
        <div class="campaign_section" id="content_section" style="display: none;">
            <h3>Set Your Content</h3>
            <hr>
            <div class="form-group">
                <label for="">Select Template</label>
                <a class="btn btn-info btn-xs float-right mb-2" href="{{route('customer.settings.index',['type'=>'settings'])}}">@lang('customer.create_template')</a>
                <select id="template" name="template_id" class="form-control" multiple>
                    @if($templates)
                        @foreach($templates as $template)
                            <option data-id="{{$template->id}}" data-body="{{$template->body}}" data-name="{{$template->title}}" {{isset($campaign) && $campaign->template_id==$template->id?'selected':''}} value="{{$template->id}}">{{$template->title}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group">
                <ul class="nav nav-tabs" id="custom_tabs_one_tab" role="tablist">

                </ul>

            </div>
            <div class="row">
                <div class="form-group col-sm-6 col-6">
                    <label for="">Template</label>
                    <div class="tab-content" id="custom_tabs_one_tabContent">

                    </div>

                </div>
                <div class="form-group col-sm-6 col-6">
                    <label class="d-block" for="">Variables</label>
                    <div>
                        @foreach(sms_template_variables() as $key=>$t)
                            <button type="button" data-name="{{$key}}"
                                    class="btn btn-sm btn-primary sms_template_variable mt-2">{{ucfirst(str_replace('_',' ',$t))}}</button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="campaign_section" id="resource_section" style="display: none;">
            <h3>Select Your Resource</h3>
            <hr>
            <div class="row mt-3">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="">Phone Numbers</label>
                        <textarea readonly="readonly" name="to_number" id="phone_numbers" cols="4" rows="12" class="form-control">{{old('to_number')?old('to_number'):''}}</textarea>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="">Groups</label>
                        <a class="btn btn-info btn-xs float-right mb-2" href="{{route('customer.groups.create')}}">@lang('customer.create_group')</a>
                        <div>
                            @foreach($groups as $group)
                                <button type="button" data-id="{{$group->id}}"
                                        class="btn btn-primary group btn-sm mt-2">{{$group->name."(".$group->contacts_count.")"}}</button>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="campaign_section d-none" id="rate_section" style="display: none;">
            <h3>Select Your Send Rate</h3>
            <hr>

            <div class="form-group mt-3">
                <label for="">Send Speed per hour</label>
                <div>
                        <span class="irs irs--flat js-irs-2"><span class="irs"><span class="irs-line"
                                                                                     tabindex="0"></span><span
                                    class="irs-min" style="visibility: hidden;">0 mm</span><span class="irs-max"
                                                                                                 style="visibility: visible;">10 mm</span><span
                                    class="irs-from" style="visibility: hidden;">0</span><span class="irs-to"
                                                                                               style="visibility: hidden;">0</span><span
                                    class="irs-single" style="left: -1.87562%;">0 mm</span></span><span
                                class="irs-grid"></span><span class="irs-bar irs-bar--single"
                                                              style="left: 0px; width: 1.57947%;"></span><span
                                class="irs-shadow shadow-single" style="display: none;"></span><span
                                class="irs-handle single" style="left: 0%;"><i></i><i></i><i></i></span></span><input
                        id="range_5" type="text" name="send_speed" value="{{isset($campaign)?$campaign->message_send_rate:''}}" class="irs-hidden-input" tabindex="-1"
                        readonly="">
                </div>
            </div>
            <div class="form-group d-none">
                <label class="d-block">Use area code if exist</label>
                <input type="checkbox" name="area_match_code" class="mr-2" id="match_code"> <label for="match_code">Areacode
                    Matching</label>
            </div>
        </div>
    </div>
</div>
