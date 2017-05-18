/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/5/2
 * Time: 09:59
 */
$(function () {
    check_page();
    $(window).resize(function () {
        check_page();
    });
});

function check_page () {
    $("#shelf").css("height", (($(window).height() - 265) + "px"));
}

function download(src) {
    var IFrameRequest = document.createElement("iframe");
    IFrameRequest.id = "IFrameRequest";
    IFrameRequest.src = src;
    IFrameRequest.style.display = "none";
    document.body.appendChild(IFrameRequest);
}

/* toastr option */
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": true,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "2000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};
