@php $m=$message @endphp
@if(count($errors)>0)
    @foreach($errors->all() as $error)
        @php $m.=$error." <br/> " @endphp
    @endforeach
@endif

<script>
    "use strict";
    $(document).Toasts('create', {
        autohide: true,
        delay: 10000,
        class: 'bg-{{$type}}',
        title: 'Notification',
        body: "{!! $m !!}"
    })
</script>
