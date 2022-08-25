<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <!-- Add icons to the links using the .nav-icon class
         with font-awesome or any other icon font library -->
    <li class="nav-item sidebar-compose">
        <a href="{{route('customer.smsbox.compose')}}" class="nav-link compose-nav-link {{isSidebarActive('customer.compose')}}">
            <i class="nav-icon fas fa-plus-circle"></i>
            <p class="quick_send-p">
                {{trans('customer.quick_send')}}
            </p>
        </a>
    </li>

    <li class="nav-item item-dashboard">
        <a href="{{route('customer.dashboard')}}" class="nav-link {{isSidebarActive('customer.dashboard')}}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
                {{trans('customer.dashboard')}}
            </p>
        </a>
    </li>

    <li class="nav-item has-treeview {{request()->get('type') == 'sent' || request()->get('type') == 'inbox' || isSidebarTrue(['customer.smsbox.overview*'])? 'menu-open' : ''}}">
        <a href="#" class="nav-link {{request()->get('type') == 'sent' || request()->get('type') == 'inbox' || isSidebarTrue(['customer.smsbox.overview*'])? 'active' : ''}}">
            <i class="nav-icon fas fa-envelope"></i>
            <p>
                {{trans('customer.messages')}}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview" style="display: {{request()->get('type') == 'sent' || request()->get('type') == 'inbox' || isSidebarTrue(['customer.smsbox.overview*'])? 'block': 'none'}}">
            <li class="nav-item item-campaign">
                <a href="{{route('customer.smsbox.overview')}}" class="nav-link {{request()->get('type') ?'':isSidebarActive('customer.smsbox.overview*')}}">
                    <i class="nav-icon fas fa-list-alt"></i>
                    <p>
                        {{trans('customer.overview')}}
                    </p>
                </a>
            </li>
            <li class="nav-item item-campaign">
                <a href="{{route('customer.smsbox.overview',['type'=>'inbox'])}}" class="nav-link {{request()->get('type') == 'inbox'?'active':''}}">
                    <i class="nav-icon fas fa-sms"></i>
                    <p>
                        {{trans('customer.inbound')}}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.smsbox.overview',['type'=>'sent'])}}"
                   class="nav-link {{request()->get('type') == 'sent'?'active':''}}">
                    <i class="nav-icon fas fa-paper-plane"></i>
                    <p>
                        {{trans('customer.outbound')}}
                    </p>
                </a>
            </li>
        </ul>
    </li>


    <li class="nav-item has-treeview {{request()->get('type') == 'settings' || isSidebarTrue(['customer.campaign.index*','customer.campaign.create*'])? 'menu-open' : ''}}">
        <a href="#" class="nav-link {{request()->get('type') == 'settings' || isSidebarTrue(['customer.campaign.index*','customer.campaign.create*'])? 'active' : ''}}">
            <i class="nav-icon fas fa-campground"></i>
            <p>
                {{trans('customer.campaigns')}}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview" style="display: {{request()->get('type') == 'settings' || isSidebarTrue(['customer.campaign.index*','customer.campaign.create*'])? 'block': 'none'}}">
            <li class="nav-item item-campaign">
                <a href="{{route('customer.campaign.create')}}" class="nav-link {{isSidebarActive('customer.campaign.create*')}}">
                    <i class="nav-icon fas fa-plus-circle"></i>
                    <p>
                        {{trans('customer.create')}}
                    </p>
                </a>
            </li>
            <li class="nav-item item-campaign">
                <a href="{{route('customer.campaign.index')}}" class="nav-link {{isSidebarActive('customer.campaign.index*')}}">
                    <i class="nav-icon fas fa-list-alt"></i>
                    <p>
                        {{trans('customer.list')}}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.settings.index',['type'=>'settings'])}}"
                   class="nav-link {{request()->get('type') == 'settings'?'active':''}}">
                    <i class="nav-icon fas fa-cube"></i>
                    <p>
                        {{trans('customer.template')}}
                    </p>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item has-treeview {{isSidebarTrue(['customer.group.records*','customer.label*','customer.from-group*','customer.contacts*','customer.groups*'])? 'menu-open' : ''}}">
        <a href="{{route('customer.contacts.index')}}" class="nav-link {{isSidebarTrue(['customer.group.records*','customer.label*','customer.contacts*','customer.groups*'])? 'active' : ''}}">
            <i class="nav-icon fas fa-phone-alt"></i>
            <p>
                {{trans('customer.contacts')}}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview" style="display: {{isSidebarTrue(['customer.group.records*','customer.label*','customer.contacts*','customer.groups*'])? 'block': 'none'}}">
            <li class="nav-item">
                <a href="{{route('customer.contacts.index')}}"
                   class="nav-link {{isSidebarActive('customer.contacts*')}}">
                    <i class="nav-icon fas fa-list-ol"></i>
                    <p>
                        {{trans('customer.list')}}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.groups.index')}}"
                   class="nav-link {{isSidebarActive('customer.groups*')}}">
                    <i class="nav-icon fas fa-window-restore"></i>
                    <p>
                        {{trans('customer.groups')}}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.group.records')}}"
                   class="nav-link {{isSidebarActive('customer.group.records*')}}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        {{trans('customer.builder')}}
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{route('customer.label.index')}}" class="nav-link {{isSidebarActive('customer.label*')}}">
                    <i class="nav-icon fas fa-tag"></i>
                    <p>
                        {{trans('customer.labels')}}
                    </p>
                </a>
            </li>

        </ul>

    </li>

    <li class="nav-item has-treeview {{request()->get('type') == 'add' || isSidebarTrue(['customer.device.index*'])? 'menu-open' : ''}}">
        <a href="#" class="nav-link {{request()->get('type') == 'add' || isSidebarTrue(['customer.device.index*'])? 'active' : ''}}">
            <i class="nav-icon fas fa-mobile"></i>
            <p>
                {{trans('customer.devices')}}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview" style="display: {{request()->get('type') == 'add' || isSidebarTrue(['customer.device.index*'])? 'block': 'none'}}">
            <li class="nav-item">
                <a href="{{route('customer.device.index',['type'=>'add'])}}" class="nav-link {{request()->get('type') == 'add'?'active':''}}">
                    <i class="nav-icon fas fa-plus-circle"></i>
                    <p>
                        {{trans('customer.add')}}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.device.index')}}" class="nav-link {{request()->get('type') == 'add'?'': isSidebarActive('customer.device.index')}}">
                    <i class="nav-icon fas fa-list-ol"></i>
                    <p>
                        {{trans('customer.list')}}
                    </p>
                </a>
            </li>
            @if(get_settings('link_apk'))
                <li class="nav-item item_download_apk">
                    <a href="#" class="nav-link" download_apk="{{get_settings('link_apk')}}"  id="download_apk">
                        <i class="nav-icon fas fa-link"></i>
                        <p>
                            {{trans('customer.download_apk')}}
                        </p>
                    </a>
                </li>
            @endif
        </ul>
    </li>


    <li class="nav-item has-treeview {{isSidebarTrue(['customer.chat.index*','customer.chat.response'])? 'menu-open' : ''}}">
        <a href="#" class="nav-link {{isSidebarTrue(['customer.chat.index*','customer.chat.response'])? 'active' : ''}}">
            <i class="nav-icon fas fa-sms"></i>
            <p>
                {{trans('customer.chat_box')}}
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview" style="display: {{isSidebarTrue(['customer.chat.index*','customer.chat.response'])? 'block': 'none'}}">
            <li class="nav-item">
                <a href="{{route('customer.chat.index')}}" class="nav-link {{isSidebarActive('customer.chat.index*')}}">
                    <i class="nav-icon fas fa-comments"></i>
                    <p>
                        {{trans('customer.chat')}}
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('customer.chat.response')}}"
                   class="nav-link {{isSidebarActive('customer.chat.response')}}">
                    <i class="nav-icon fas fa-mobile-alt"></i>
                    <p>
                        {{trans('customer.chat_responses')}}
                    </p>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item item-billing">
        <a href="{{route('customer.billing.index')}}" class="nav-link {{isSidebarActive('customer.billing*')}}">
            <i class="nav-icon fas fa-file-invoice-dollar"></i>
            <p>
                {{trans('customer.billing')}}
            </p>
        </a>
    </li>

    <li class="nav-item item-api-token">
        <a href="{{route('customer.authorization.token.create')}}" class="nav-link {{isSidebarActive('customer.authorization.token.create')}}">
            <i class="fas fa-code nav-icon"></i>
            <p>
                {{trans('customer.developer')}}
            </p>
        </a>
    </li>

    <li class="nav-item item-ticket">
        <a href="{{route('customer.ticket.index')}}" class="nav-link {{isSidebarActive('customer.ticket.index')}}">
            <i class="nav-icon fas fa-exclamation-triangle"></i>
            <p>
                {{trans('customer.ticket.ticket')}}
            </p>
        </a>
    </li>
    <li class="nav-item item-settings">
        <a href="{{route('customer.settings.index')}}" class="nav-link {{request()->get('type') == 'settings'?'':isSidebarActive('customer.settings*') }}">
            <i class="nav-icon fas fa-cog"></i>
            <p>
                {{trans('customer.settings')}}
            </p>
        </a>
    </li>
</ul>
