
(function($) {
    "use strict";
    $('#modal-confirm').on('show.bs.modal', function (e) {
        const button=$(e.relatedTarget);
        const message=button.attr('data-message');
        const method=button.attr('data-method')?button.attr('data-method'):'post';
        const action=button.attr('data-action');
        const input=JSON.parse(button.attr('data-input'));
        let div='';
        $.each(input,function(index,value){
            div+=`<input type="hidden" name=${index} value=${value}>`;
        });

        $('#modal-confirm .modal-body').html(message);
        $('#modal-form').attr('method',method).attr('action',action);
        $('#modal-form #customInput').html(div);
        $('#modal-confirm-btn').attr('type','submit');
    })
})(jQuery);

function toggleSection(from,to){
    "use strict";
    $(from).hide();
    $(to).show();

}

function notify(type,message) {
    "use strict";
    $(document).Toasts('create', {
        autohide: true,
        delay: 3000,
        class: 'bg-'+type,
        title: 'Notification',
        body: message
    })
}

function remove_readonly(e) {
    "use strict";
    e.removeAttribute('readonly');
}
