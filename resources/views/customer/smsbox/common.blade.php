<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{trans('customer.folders')}}</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item active">
                <a href="{{route('customer.smsbox.inbox')}}" class="nav-link">
                    <i class="fas fa-inbox"></i> {{trans('customer.inbound')}}
                    <span class="badge bg-primary float-right">{{auth('customer')->user()->unread_messages()->count()}}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.smsbox.sent')}}" class="nav-link">
                    <i class="far fa-envelope"></i> {{trans('customer.outbound')}}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.smsbox.draft')}}" class="nav-link">
                    <i class="far fa-file-alt"></i> {{trans('customer.draft')}}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.smsbox.trash')}}" class="nav-link">
                    <i class="far fa-trash-alt"></i> {{trans('customer.trash')}}
                </a>
            </li>
        </ul>
    </div>
    <!-- /.card-body -->
</div>
