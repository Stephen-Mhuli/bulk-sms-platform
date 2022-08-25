@php
        $emailTemplateReg = get_email_template('registration');
        $emailTemplatePass = get_email_template('forget_password');
        $emailTemplatePlanRequest = get_email_template('plan_request');
        $emailTemplatePlanAccepted = get_email_template('plan_accepted');
        $emailTemplatePlanExpire = get_email_template('plan_expired');
@endphp

<div class="pt-4">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-4">
                        <div class="nav nav-pills mb-3 nav-flex" id="email_temp">
                            <a href="#v-pills-registration" data-toggle="pill"
                               class="nav-link active  custom-nav flex-content">{{trans('admin.settings.registration')}}</a>
                            <a href="#v-pills-forgetPass" data-toggle="pill"
                               class="nav-link custom-nav flex-content">{{trans('admin.settings.forget_password')}}</a>
                            <a href="#v-pills-planRequest" data-toggle="pill"
                               class="nav-link custom-nav d-block flex-content">{{trans('admin.settings.plan_request')}}</a>
                            <a href="#v-pills-planAccepted" data-toggle="pill"
                               class="nav-link custom-nav flex-content">{{trans('admin.settings.plan_accept')}}</a>
                            <a href="#v-pills-planExpire" data-toggle="pill"
                               class="nav-link custom-nav flex-content">{{trans('admin.settings.plan_expire')}}</a>

                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="tab-content">
                            <div id="v-pills-registration"
                                 class="tab-pane fade active show">
                                <form action="{{route('admin.settings.email.template.store')}}"
                                      method="post">
                                    @csrf
                                    @isset($emailTemplateReg)
                                        <input type="hidden"
                                               value="{{$emailTemplateReg->id}}"
                                               name="emailTemplateID">
                                    @endisset
                                    <input type="hidden" name="type"
                                           value="{{isset($emailTemplateReg->type)?$emailTemplateReg->type:'registration'}}">
                                    <textarea class="form-control" name="subject"
                                              rows="2"
                                              placeholder="{{trans('admin.settings.email_subject')}}">{{isset($emailTemplateReg->subject)?$emailTemplateReg->subject:''}}</textarea>
                                    <textarea class="form-control mt-2" name="body"

                                              rows="5"
                                              placeholder="{{trans('admin.settings.email_body')}}">{{isset($emailTemplateReg->body)?$emailTemplateReg->body:''}}</textarea>

                                    <div>{customer_name} = Customer Name</div>
                                    <div>{click_here} = For verification link</div>
                                    <button type="submit"
                                            class="btn btn-primary btn-sm  float-right mt-4">
                                        {{trans('admin.settings.submit')}}
                                    </button>
                                </form>
                            </div>

                            <div id="v-pills-forgetPass" class="tab-pane fade">
                                <form action="{{route('admin.settings.email.template.store')}}"
                                      method="post">
                                    @csrf
                                    @isset($emailTemplatePass)
                                        <input type="hidden"
                                               value="{{$emailTemplatePass->id}}"
                                               name="emailTemplateID">
                                    @endisset
                                    <input type="hidden" name="type"
                                           value="{{isset($emailTemplatePass->type)?$emailTemplatePass->type:'forget_password'}}">
                                    <textarea class="form-control" name="subject"
                                              rows="2"
                                              placeholder="{{trans('admin.settings.email_subject')}}">{{isset($emailTemplatePass->subject)?$emailTemplatePass->subject:''}}</textarea>
                                    <textarea class="form-control mt-2" name="body"

                                              rows="5"
                                              placeholder="{{trans('admin.settings.email_body')}}">{{isset($emailTemplatePass->body)?$emailTemplatePass->body:''}}</textarea>
                                    <div>{customer_name} = Customer Name</div>
                                    <div>{reset_url} = Reset URL Link</div>
                                    <button type="submit"
                                            class="btn btn-primary btn-sm  float-right mt-4">
                                        {{trans('admin.settings.submit')}}
                                    </button>
                                </form>
                            </div>

                            <div id="v-pills-orderPlaced" class="tab-pane fade">
                            </div>

                            <div id="v-pills-planRequest" class="tab-pane fade">
                                <form action="{{route('admin.settings.email.template.store')}}"
                                      method="post">
                                    @csrf
                                    @isset($emailTemplatePlanRequest)
                                        <input type="hidden"
                                               value="{{$emailTemplatePlanRequest->id}}"
                                               name="emailTemplateID">
                                    @endisset
                                    <input type="hidden" name="type"
                                           value="{{isset($emailTemplatePlanRequest->type)?$emailTemplatePlanRequest->type:'plan_request'}}">
                                    <textarea class="form-control" name="subject"
                                              rows="2"
                                              placeholder="{{trans('admin.settings.email_subject')}}">{{isset($emailTemplatePlanRequest->subject)?$emailTemplatePlanRequest->subject:''}}</textarea>
                                    <textarea class="form-control mt-2" name="body"

                                              rows="5"
                                              placeholder="{{trans('admin.settings.email_body')}}">{{isset($emailTemplatePlanRequest->body)?$emailTemplatePlanRequest->body:''}}</textarea>

                                    <div>{customer_name} = Customer Name</div>
                                    <button type="submit"
                                            class="btn btn-primary btn-sm  float-right mt-4">
                                        {{trans('admin.settings.submit')}}
                                    </button>
                                </form>
                            </div>

                            <div id="v-pills-planAccepted" class="tab-pane fade">
                                <form action="{{route('admin.settings.email.template.store')}}"
                                      method="post">
                                    @csrf
                                    @isset($emailTemplatePlanAccepted)
                                        <input type="hidden"
                                               value="{{$emailTemplatePlanAccepted->id}}"
                                               name="emailTemplateID">
                                    @endisset
                                    <input type="hidden" name="type"
                                           value="{{isset($emailTemplatePlanAccepted->type)?$emailTemplatePlanAccepted->type:'plan_accepted'}}">
                                    <textarea class="form-control" name="subject"
                                              rows="2"
                                              placeholder="{{trans('admin.settings.email_subject')}}">{{isset($emailTemplatePlanAccepted->subject)?$emailTemplatePlanAccepted->subject:''}}</textarea>
                                    <textarea class="form-control mt-2" name="body"

                                              rows="5"
                                              placeholder="{{trans('admin.settings.email_body')}}">{{isset($emailTemplatePlanAccepted->body)?$emailTemplatePlanAccepted->body:''}}</textarea>

                                    <div>{customer_name} = Customer Name</div>
                                    <button type="submit"
                                            class="btn btn-primary btn-sm  float-right mt-4">
                                        {{trans('admin.settings.submit')}}
                                    </button>
                                </form>
                            </div>

                            <div id="v-pills-planExpire" class="tab-pane fade">
                                <form action="{{route('admin.settings.email.template.store')}}"
                                      method="post">
                                    @csrf
                                    @isset($emailTemplatePlanExpire)
                                        <input type="hidden"
                                               value="{{$emailTemplatePlanExpire->id}}"
                                               name="emailTemplateID">
                                    @endisset
                                    <input type="hidden" name="type"
                                           value="{{isset($emailTemplatePlanExpire->type)?$emailTemplatePlanExpire->type:'plan_expired'}}">
                                    <textarea class="form-control" name="subject"
                                              rows="2"
                                              placeholder="{{trans('admin.settings.email_subject')}}">{{isset($emailTemplatePlanExpire->subject)?$emailTemplatePlanExpire->subject:''}}</textarea>
                                    <textarea class="form-control mt-2" name="body"

                                              rows="5"
                                              placeholder="{{trans('admin.settings.email_body')}}">{{isset($emailTemplatePlanExpire->body)?$emailTemplatePlanExpire->body:''}}</textarea>

                                    <div>{customer_name} = Customer Name</div>
                                    <div>{click_here} = Plan URL Link</div>
                                    <div>{plan_expired_date} = Plan Expired Date</div>
                                    <button type="submit"
                                            class="btn btn-primary btn-sm  float-right mt-4">
                                        {{trans('admin.settings.submit')}}
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
