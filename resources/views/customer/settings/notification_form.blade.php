<table class="w-100">
    <tr>
        <td><label>{{trans('customer.email_notification')}}</label></td>
        <td>
            <div class="form-group mt-2">
                <div class="custom-control custom-switch">
                    <input {{isset($customer_settings['email_notification']) && $customer_settings['email_notification']=='true'?'checked':''}} type="checkbox" class="custom-control-input" id="notification_switch">
                    <label class="custom-control-label" for="notification_switch"></label>
                </div>
            </div>
        </td>
        <td></td>
        <td></td>
    </tr>

    <tr>
        <td><label for="">Webhook</label></td>
        <td>
            <select name="" id="webhook_type" class="form-control">
                <option {{isset($customer_settings['webhook']) && json_decode($customer_settings['webhook'])->type=='get'?'selected':''}} value="get">GET</option>
                <option {{isset($customer_settings['webhook']) && json_decode($customer_settings['webhook'])->type=='post'?'selected':''}} value="post">POST</option>
            </select>
        </td>
        <td>
            <input value="{{isset($customer_settings['webhook']) && json_decode($customer_settings['webhook'])->url?json_decode($customer_settings['webhook'])->url:''}}" type="text" id="webhook_url" required class="form-control" placeholder="Enter webhook url">
        </td>
        <td><button id="webhookSubmit" type="button" class="btn btn-primary ml-2">Save</button></td>
    </tr>

    <tr class="mt-3">
        <td><label for="">Data Posting</label></td>
        <td>
            <select name="" id="data_posting_type" class="form-control">
                <option {{isset($customer_settings['data_posting']) && json_decode($customer_settings['data_posting'])->type=='get'?'selected':''}} value="get">GET</option>
                <option {{isset($customer_settings['data_posting']) && json_decode($customer_settings['data_posting'])->type=='post'?'selected':''}} value="post">POST</option>
            </select>
        </td>
        <td>
            <input value="{{isset($customer_settings['data_posting']) && json_decode($customer_settings['data_posting'])->url?json_decode($customer_settings['data_posting'])->url:''}}" type="text" id="data_posting_url" required class="form-control" placeholder="Data Posting URL">
        </td>
        <td><button id="dataPostIngSubmit" type="button" class="btn btn-primary ml-2">Save</button></td>
    </tr>
</table>
