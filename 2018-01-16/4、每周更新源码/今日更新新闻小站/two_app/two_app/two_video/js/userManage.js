/**
 * Created by Aber on 17/3/17.
 */
// 表格操作
if(checklogin()){
    $(function () {
        window.operateEvents = {
            'click .remove': function (e, value, row, index) {
                $table.bootstrapTable('remove', {
                    field: 'memberid',
                    values: [row.memberid]
                });
                del([row.memberid]) //删除
            }
        };function
        operateFormatter(value, row, index) {
            return [
                '<a class="remove" href="" title="Remove">',
                '<i class="glyphicon glyphicon-remove"></i>',
                '</a>'
            ].join('');
        }
        var $table = $('#tables'),
            $remove = $('#remove'),
            selections = [];

// 列按钮操作
        //$id, $offset, $limit, $search, $order, $ordername
        function init() {
            //初始化Table
            req("/sys_member/listPage",{limit:10,offset:0},function (result) {
                $table.bootstrapTable({
                    pagination: true,
                    showpaginationswitch: true,
                    paginationPreText: "上一页",
                    paginationNextText: "下一页",
                    singleSelect: false,
                    cache:true,
                    cardView: false,                    //是否显示详细视图
                    detailView: true,
                    detailFormatter:detailFormatter,
                    pageNumber : 1,
                    uniqueId: "memberid",
                    pageList: [5, 10, 20, 50,100],//分页步进值
                    sidepagination: "client",
                    showToggle: false,
                    showColumns: true,
                    showRefresh: false,
                    striped: true,
                    sortable: true,
                    sortStable:true,
                    sortOrder: "asc",                   //排序方式
                    //showRefresh: true, //是否显示刷新按钮
                    clickToSelect: true, //是否启用点击选中行
                    columns: [
                        {
                            field: 'state',
                            checkbox: true
                        },
                        {
                            title: '用户id',//标题
                            field: 'memberid'//域值
                        },{
                            title: "用户名",//标题
                            field: "name",//键名
                            editable:{
                                type:'text',
                                title:'用户名',
                                validate:function(value) {
                                    if ($.trim(value) == '') {
                                        return '不能为空';//修改是数据为空 显示
                                    }
                                }
                            }
                        },

                        {
                            title: "手机",//标题
                            field: "mobile",//键名
                            editable:{
                                type:'text',
                                title:'手机',
                                validate:function(value) {
                                    if ($.trim(value) == '') {
                                        return '不能为空';//修改是数据为空 显示
                                    }
                                }
                            }
                        },
                        {
                            title: "登陆名",//标题
                            field: "username"//键名
                        },
                        {
                            title: "密码",//标题
                            field: "password",//键名
                            editable:{
                                type:'text',
                                title:'密码',
                                validate:function(value) {
                                    if ($.trim(value) == '') {
                                        return '不能为空';//修改是数据为空 显示
                                    }
                                }
                            }
                        },
                        {
                            title: "用户类型",//标题
                            field: "role",//键名
                            // editable:true
                            editable:{
                                type:'select',
                                title:'用户类型',
                                source:[{value:1,text:"管理员"},{value:2,text:"技术员"},{value:3,text:"业务员"}],
                                validate:function(value) {
                                    if ($.trim(value) == '') {
                                        return '不能为空';//修改是数据为空 显示
                                    }
                                }
                            }

                        },
                        {
                            title: '操作',
                            field: 'carousel',
                            align: 'center',
                            valign: "middle",//垂直
                            events: operateEvents,
                            formatter: operateFormatter,
                            cellStyle: function (value, row, index) {
                                return {
                                    css: {
                                        "max-hight": "100px",
                                        "word-wrap": "no-wrap",
                                        "word-break": "normal",
                                        "overflow": "hidden"
                                    }
                                };
                            }
                        }

                    ],data:result
                    ,onEditableSave:function (field,row,old,$el) {
                        console.log(field,row,old);
                        $("#tables").bootstrapTable("resetView");
                        update(row)
                    }
                });
            })
            setTimeout(function () {
                $table.bootstrapTable('resetView');
            }, 200);
            $table.on('check.bs.table uncheck.bs.table ' +
                'check-all.bs.table uncheck-all.bs.table', function () {
                $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
                // save your data, here just save the current page
                selections = getIdSelections();
                // push or splice the selections if you want to save all data selections
            });
            // $table.on('expand-row.bs.table', function (e, index, row, $detail) {
            //     if (index % 2 == 1) {
            //         $detail.html('Loading from ajax request...');
            //         $.get('/user/deatil', function (res) {
            //             $detail.html(res.replace(/\n/g, '<br>'));
            //         });
            //     }
            // });
            $table.on('all.bs.table', function (e, name, args) {
                // console.log(name, args);
                //修改
            });

            $remove.click(function () {
                var ids = getIdSelections();
                $table.bootstrapTable('remove', {
                    field: 'memberid',
                    values: ids
                });
                $remove.prop('disabled', true);
                del(ids)//删除
            });
            $(window).resize(function () {
                $table.bootstrapTable('resetView', {
                    height: getHeight()
                });
            });
            function getIdSelections() {
                return $.map($table.bootstrapTable('getSelections'), function (row) {
                    return row.memberid
                });
            }

            // function responseHandler(res) {
            //     $.each(res.rows, function (i, row) {
            //         row.state = $.inArray(row.id, selections) !== -1;
            //     });
            //     return res;
            // }
            //
            function detailFormatter(index, row) {
                var html = [];
                $.each(row, function (key, value) {
                    if(key=="password"){
                        html.push('<p><b>' + "密码" + ':</b> ' + value + '</p>');
                    }
                    if(key=="username"){
                        html.push('<p><b>' + "登陆名" + ':</b> ' + value + '</p>');
                    }
                });
                return html.join('');
            }
        };
        function getHeight() {
            return $(window).height() - $('h1').outerHeight(true);
        }
        // function totalTextFormatter(data) {
        //     return 'Total';
        // }function totalNameFormatter(data) {
        //     return data.length;
        // }
        // function totalPriceFormatter(data) {
        //     var total = 0;
        //     $.each(data, function (i, row) {
        //         total += +(row.price.substring(1));
        //     });
        //     return '$' + total;
        // }
        init()
        //------------------
        $("#addsave").click(function () {
            add()
        })
        $('#myModal').on('hidden.bs.modal', function (e) {
            var name=$("#name").val(""),
                mobile=$("#mobile").val(""),

                username=$("#username").val(""),
                password=$("#password").val("");
        })
        //修改用户
        function update(object) {
            console.log(object)
            req("/sys_member/update",object,function (d) {
                if(d.status){
                    success("修改成功")
                }else if(d.status===0){
                    error("修改失败")
                }
            })
        }
        //删除用户
        function del(array) {
            for(var i=0;i<array.length;i++){
                req("/sys_member/delete",{memberid:array[i]},function (d) {
                    if(d.status){
                        success("删除成功")
                    }else if(d.status===0){
                        error("删除失败")
                    }
                })
            }
        }
        //保存用户
        function add() {
            var name=$("#name").val(),
                mobile=$("#mobile").val(),
                role=$("#role").val(),
                username=$("#username").val(),
                password=$("#password").val();
            if(name&&mobile){
                req("/sys_member/create",{name:name,mobile:mobile,role:role,username:username,password:password},function (d) {
                    if(d.status){
                        success("创建成功")
                        $("#tables").bootstrapTable("insertRow",{index:0, row: d.data});
                    }else if(d.status===0){
                        error("创建失败")
                    }
                })
                $('#myModal').modal("hide")
            }else{
                error("不能为空")
            }
        }

        $("#username").blur(function () {
            req("/sys_member/findOne",{username:$(this).val()},function (d) {
                if(d.status){
                    error("登陆名已存在")
                    $("#username").val("")

                }
            })
        })
    })
}else{
    location.href=location.origin+"/arpt-official/html/login.html"
}
