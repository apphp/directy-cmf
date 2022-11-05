/**
 * My alert
 * @param string message
 * @param string type
 * @return void
 */
function apAlert(message, type){
    if(message === null){
        return false;
    }
    type = type == null ? 'info' : type;

    // Init toastr
    // See: http://codeseven.github.io/toastr/demo.html
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    toastr[type](message);
}
