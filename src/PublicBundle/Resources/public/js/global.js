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
    $(".tool-tip").tooltip();
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

/* Validate Password */
function validatePasswd(input) {
    var arr = [];
    var patrn = /^(\w)*$/;

    if (input.val().length < 6 || input.val().length >12) {
        arr = ["error", "success", "您的密码长度应为6-12位！"];
    } else if (!patrn.exec(input.val())) {
        arr = ["error", "success", "您的密码长度必须为数字、字母或下划线!"];
    } else {
        arr = ["success","error",  "{{ msg }}"];
    }
    input.closest("div.form-group").addClass("has-" + arr[0]).removeClass("has-" + arr[1]).find(".help-block").html(arr[2]);
}
function checkPasswd(input){
    var val = [], arr = [];
    input.each(function (index) {
        val[index] = $(this).val();
    });

    if ($(this).closest("div.has-success").size() === 3) {
        return false;
    }
    if (val[1] !== val[2]) {
        arr = ["error", "success", "您输入的两次密码不一致，请重新输入！"];
        input.eq(2).closest("div.form-group").addClass("has-" + arr[0]).removeClass("has-" + arr[1]).find(".help-block").html(arr[2]);
        return false;
    }

    return true;
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
