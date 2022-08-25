<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <!-- Add icons to the links using the .nav-icon class
         with font-awesome or any other icon font library -->
    <li class="nav-item item-dashboard">
        <a href="{{route('admin.dashboard')}}" class="nav-link {{isSidebarActive('admin.dashboard')}}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
                {{trans('customer.dashboard')}}
            </p>
        </a>
    </li>
    <li class="nav-item item-customers">
        <a href="{{route('admin.customers.index')}}" class="nav-link {{isSidebarActive('admin.customers*')}}">
            <i class="nav-icon fas fa-user-friends"></i>
            <p>
                {{trans('customer.customers')}}
            </p>
        </a>
    </li>
    <li class="nav-item item-plan">
        <a href="{{route('admin.plans.index')}}" class="nav-link {{isSidebarActive('admin.plans*')}}">
            <i class="nav-icon fas fa-file-signature"></i>
            <p>
               {{trans('customer.plan')}} <span class="plan_pending_count">{{pendingPlanRequest()}}</span>
            </p>
        </a>
    </li>
    <li class="nav-item item-ticket">
        <a href="{{route('admin.ticket.index')}}" class="nav-link {{isSidebarActive('admin.ticket*')}}">
            <i class="nav-icon fas fa-exclamation-triangle"></i>
            <p>
                {{trans('admin.ticket.ticket')}}
            </p>
        </a>
    </li>
   <li class="nav-item item-settings">
        <a href="{{route('admin.settings.index')}}" class="nav-link {{isSidebarActive('admin.settings*')}}">
            <i class="nav-icon fas fa-cog"></i>
            <p>
                {{trans('customer.settings')}}
            </p>
        </a>
    </li>

    <li class="nav-item item-addon d-none">
        <a href="{{route('admin.addon.index')}}" class="nav-link {{isSidebarActive('admin.addon*')}}">
            <i class="nav-icon fas fa-boxes"></i>
            <p>
                {{trans('admin.addon.addon')}}
            </p>
        </a>
    </li>
</ul>
