/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/5/4
 * Time: 11:58
 */
$(function () {
    // 单个添加到货架
    $(document).on("click", ".add-block .add-btn", function() {
        if (!$(this).hasClass("disabled")) {
            var shelfBox = $(this).closest(".shelf-box");
            changeStyle(shelfBox);
            addOneShelfBox(shelfBox);
            if ($(".shelf-old-data .shelf-box:not(.added)").length === 0) {
                $(".add-all-btn").addClass("display-hide");
                $(".delete-all-btn").removeClass("display-hide");
            }
            toastr.success("该商品添加成功！", "提示", {timeOut: "1000"});
        } else {
            toastr.warning("该商品已添加！", "提示", {timeOut: "1000"});
        }
    });

    // 全部添加到货架
    $(document).on("click", ".add-all-btn", function () {
        $(this).addClass("display-hide");
        $(".delete-all-btn").removeClass("display-hide");
        $(".shelf-old-data .shelf-box:not(.added)").each(function () {
            changeStyle($(this));
            addOneShelfBox($(this));
        });
        toastr.success("该商品已全部添加成功！", "提示", {timeOut: "1000"});
    });

    // 全部删除出货架
    $(document).on("click", ".delete-all-btn", function () {
        $(this).addClass("display-hide");
        $(".add-all-btn").removeClass("display-hide");
        $(".shelf-new-data").empty();
        $(".shelf-old-data .added").each(function () {
            recoverStyle($(this));
        });
        toastr.success("该商品已全部移除！", "提示", {timeOut: "1000"});
    });

    // 显示鼠标工具栏
    $(document).on("mouseenter", ".shelf-new-data div[data-index]", function() {
        var shelfBox = $(this);
        shelfBox.css({ "position": "relative" });
        var bw = shelfBox.width();
        var bh = shelfBox.height();
        var arr = new Array("up", "left", "right", "down");

        var editTmp = '';
        editTmp += '<div class="edit-tool" style="width:' + bw + 'px;height:' + bh + 'px;">';
        editTmp += '<button type="button" class="btn btn-default tool-btn edit-sale-out"> 售罄 </button>';
        editTmp += '<button type="button" class="btn btn-default tool-btn edit-delete"> 移除 </button>';
        editTmp += '<button type="button" class="btn btn-default tool-btn edit-replace"> 替换 </button>';
        for (var i = 0; i < 4; i++) {
            editTmp += '<button type="button" class="btn green tool-arrow-btn btn-' + arr[i] + '"><span class="icon icon-angle-' + arr[i] + '"></span></button>';
        }
        editTmp += '</div>';

        shelfBox.append(editTmp);
        return false;
    });

    // 元素左右移动
    $(document).on('click', '.btn-left,.btn-right', function() {
        // 获取当前模块的列位置
        var box = $(this).parents('div[data-index]');
        var idx1 = $(this).closest('.shelf-data').children('div').length;
        var idx2 = $(this).closest('.shelf-data').find('div[data-index]').length;
        var idx = $(box).index() - idx1 + idx2;

        // 当前模块在第几列 得出结果为0是第num列
        var curCol = (idx + 1) % lineNum;
        if (curCol === 0) {
            curCol = lineNum;
        }

        // 向左移动
        if ($(this).hasClass('btn-left')) {
            if (curCol !== 1) {
                replaceBox(box, idx - 1);
            }
            // 向右移动
        } else {
            if (curCol !== lineNum) {
                replaceBox(box, idx + 1);
            }
        }
    });

    // 元素上下移动
    $(document).on('click', '.btn-up,.btn-down', function() {
        var box = $(this).parents('div[data-index]');
        var idx1 = $(this).closest('.shelf-data').children('div').length;
        var idx2 = $(this).closest('.shelf-data').find('div[data-index]').length;
        var idx = $(box).index() - idx1 + idx2;
        var total = $(".new-shelf-data div[data-index]").size();

        // 一行放num个,算出总共有几行
        var rowsNum = Math.ceil(total / lineNum);
        // 得出当前模块在第几行
        var curRow = Math.ceil((idx + 1) / lineNum);

        // 当前模块在第几列 得出结果为0是第num列
        var curCol = (idx + 1) % lineNum;
        if (curCol === 0) {
            curCol = lineNum;
        }

        // 向上移动
        if ($(this).hasClass('btn-up')) {
            if (curRow !== 1) {
                // 得出上面一行对应的元素
                var upRow = curRow - 1;
                // toto 算出上面元素的位置
                var upIdx = (upRow - 1) * lineNum + curCol - 1;
                replaceBox(box, upIdx);
            }
            // 向下移动
        } else {
            // 得出下面一行对应的元素
            if (curRow !== rowsNum) {
                // toto 算出上面元素的位置
                var downIdx = curRow * lineNum + curCol - 1;
                replaceBox(box, downIdx);
            }
        }
    });

    // 复制代码
    var clip = new ZeroClipboard($(".copy-code-btn"));
    clip.on("copy", function () {
        clip.setText($(".code-textarea").val());
    });
    clip.on("aftercopy", function () {
        toastr.success("复制成功！", "提示");
    });
});

// 修改已经添加后的样式
function changeStyle(shelfBox) {
    $(shelfBox).addClass("added").find(".add-btn").text("已经添加").removeClass("btn-info").addClass("btn-default disabled");
}

// 恢复添加前的样式
function recoverStyle(shelfBox) {
    shelfBox.removeClass("added").find(".add-btn").text("添加到货架").addClass("btn-info").removeClass("disabled btn-default");
}

// 添加一个到new-shelf
function addOneShelfBox(shelfBox) {
    var shelfBoxClone = shelfBox.clone();
    shelfBoxClone.find(".add-block").remove()
    $(".shelf-new-data").append(shelfBoxClone);
}

// 元素内容的替换
function replaceBox(box, curIdx) {
    var curBox = $(".shelf-new-data div[data-index]").eq(curIdx);
    var boxIDx = curBox.attr('data-index');
    curBox.css({ 'position': 'relative' });
    var cont = $(curBox).html();
    var curCont = $(box).html();
    var curBoxIdx = box.attr('data-index');

    curBox.html(curCont);
    curBox.attr('data-index', curBoxIdx);
    $(box).html(cont);
    $(box).attr('data-index', boxIDx);
}