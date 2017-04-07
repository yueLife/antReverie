function datatable(dom) {
    dom.dataTable({
        "language": {
            "aria": {
                "sortAscending": ": 按升序排序",
                "sortDescending": ": 按降序排序"
            },
            "emptyTable": "表中没有记录",
            "info": "共 _TOTAL_ 条记录，已显示 _START_ 到 _END_ 条",
            "infoEmpty": "找不到记录",
            "infoFiltered": "(已从 _MAX_ 条记录过滤)",
            "lengthMenu": "一页 _MENU_ 条记录",
            "search": "搜索:",
            "zeroRecords": "找不到匹配的记录",
            "paginate": {
                "previous":"上页",
                "next": "下页",
                "last": "末页",
                "first": "首页",
                "page": "",
                "pageOf": "共"
            }
        },

        buttons: [],
        scrollX:        true,
        deferRender:    true,

        "order": [
            [0, 'desc']
        ],

        "lengthMenu": [
            [5, 10, 15, 20, -1],
            [5, 10, 15, 20, "All"]
        ],
        "pageLength": 5,
        "pagingType": "bootstrap_extended",

        "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>"
    });
};
