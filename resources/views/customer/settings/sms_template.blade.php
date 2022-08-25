
<div class="form-group">
    <button class="btn btn-primary float-right mb-3 btn-sm" data-title="Add New Template" type="button" id="addNewTemplate">{{trans('customer.add_template')}}</button>
</div>
<div class="card-body table-responsive p-0 " style="height: 300px;overflow-x: auto!important;">
    <table class="table table-head-fixed text-nowrap text-center">
        <thead>
        <tr>
            <th>{{trans('customer.title')}}</th>
            <th>{{trans('customer.status')}}</th>
            <th>{{trans('customer.action')}}</th>
        </tr>
        </thead>
        <tbody>
        @if($sms_templates->isNotEmpty())
        @foreach($sms_templates as $sms_template)
        <tr>
            <td>{{$sms_template->title}}</td>
            <td>@if($sms_template->status=='active')
                    <span class="pl-2 pr-2 pt-1 pb-1 bg-success" style="border-radius:25px;">Active</span>
                @else
                    <span class="pl-2 pr-2 pt-1 pb-1 bg-success" style="border-radius:25px;">Inactive</span>
                @endif
            </td>
            <td><button type="button" data-value="{{json_encode($sms_template->only(['id','title','status','body']))}}" class="btn btn-sm btn-info template-edit"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-danger" type="button" data-message="Are you sure you want to delete this template?"
                        data-action="{{route('customer.sms.template.delete',['id'=>$sms_template->id])}}"
                data-input={"_method":"delete"}
                data-toggle="modal" data-target="#modal-confirm"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
        @endforeach
        @else
            <tr>
                <td></td>
                <td colspan="1">{{trans('customer.no_data_available')}}</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

