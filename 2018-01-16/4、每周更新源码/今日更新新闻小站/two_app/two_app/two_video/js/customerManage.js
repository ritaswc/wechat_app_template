/**
 * Created by Aber on 17/3/20.
 */
// 表格操作
if(checklogin()){
    $(function () {
        window.operateEvents = {
            'click .check': function (e, value, row, index) {
                row.type=2;
                success("审核中.....")
                update(row)

                setTimeout(function () {
                    location.reload()
                }, 500);
            },
            'click .addDevice':function (e, value, row, index) {
                $('#myModal1').modal("show")
                sessionStorage.setItem("cus",row.customerid)
                getCus(row.customerid);
            }
        };function
        operateFormatter(value, row, index) {
            // if(row.customerid=='arpt02'){
            //     return [
            //         '<span class="" disabled style="color:green" href="javascript:void(0)" title="Remove">',
            //         '超级管理员',
            //         '</span>'
            //     ].join('');
            // }
            var object=[];
            if(row.type==2){
                object=[
                    '<span class="" disabled style="color:green" href="javascript:void(0)" title="Remove">',
                    '已经审核',
                    '</span>',
                    '<a class="addDevice"  style="margin-left: 5px" href="javascript:void(0)" title="Remove">',
                    '添加设备',
                    '</a>'
                ];
            }else{
                object=[
                    '<a class="check" href="javascript:void(0)" title="Remove">',
                    '按我审核',
                    '</a>',
                    '<a class="addDevice"  style="margin-left: 5px" href="javascript:void(0)" title="Remove">',
                    '添加设备',
                    '</a>'
                ];
            }
            return object.join('');
        }
        var $table = $('#tables'),
            $remove = $('#remove'),
            selections = [];

// 列按钮操作
        //$id, $offset, $limit, $search, $order, $ordername
        function init() {
            //初始化Table
            req("/customer/listPage",{limit:10,offset:0},function (result) {
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
                    uniqueId: "customerid",
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
                    // clickToSelect: true, //是否启用点击选中行
                    columns: [
                        {
                            field: 'state',
                            checkbox: true
                        },
                        {
                            title: '客户id',//标题
                            field: 'customerid'//域值
                        },
                        {
                            title: "客户名",//标题
                            field: "name",//键名
                            editable:{
                                type:'text',
                                title:'客户名',
                                validate:function(value) {
                                    if ($.trim(value) == '') {
                                        return '不能为空';//修改是数据为空 显示
                                    }
                                }
                            }
                        },
                        {
                            title: "联系电话",//标题
                            field: "mobile",//键名
                            // editable:true
                            editable:{
                                type:'text',
                                title:'联系电话',
                                validate:function(value) {
                                    if ($.trim(value) == '') {
                                        return '不能为空';//修改是数据为空 显示
                                    }
                                }
                            }

                        },
                        {
                            title: "商铺名称",//标题
                            field: "shopname",//键名
                            // editable:true
                            editable:{
                                type:'text',
                                title:'商铺名称',
                                validate:function(value) {
                                    if ($.trim(value) == '') {
                                        return '不能为空';//修改是数据为空 显示
                                    }
                                }
                            }

                        },
                        {
                            title: "商铺地址",//标题
                            field: "shopaddress",//键名
                            // editable:true
                            editable:{
                                type:'text',
                                title:'商铺地址',
                                validate:function(value) {
                                    if ($.trim(value) == '') {
                                        return '不能为空';//修改是数据为空 显示
                                    }
                                }
                            }

                        },
                        {
                            title: "状态",//标题
                            field: "type",//键名
                            // editable:true
                            editable:{
                                type:'select',
                                title:'状态',
                                source:[{value:1,text:"待审"},{value:2,text:"已审核"}],
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
            //         $.get('/customerid/deatil', function (res) {
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
                    field: 'customerid',
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
                    return row.customerid
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
                        html.push('<p><b>' + "用户名" + ':</b> ' + value + '</p>');
                    }
                });
                return html.join('');
            }
        };
        function getHeight() {
            return $(window).height() - $('h1').outerHeight(true);
        }

        init()
        //------------------
        $("#addsave").click(function () {
            add()
        })
        $('#myModal').on('hidden.bs.modal', function (e) {
            var name=$("#name").val(""),
                mobile=$("#mobile").val("")
        })
        $("#typeSelect").change(function () {
            search("type",$(this).val())
        })
        //查找
        function search(key,value) {
            if(value==1||value==2){
                req("/customer/search",{key:key,value:value,startDatetime:"",endDatetime:""},function (d) {
                    $("#tables").bootstrapTable("load",d)
                })
            }else{
                req("/customer/listPage",{limit:10,offset:0},function (d) {
                    $("#tables").bootstrapTable("load",d)
                })
            }
        }
        //修改客户
        function update(object) {
            req("/customer/update",object,function (d) {
                if(d.status){
                    success("修改成功")
                }else if(d.status===0){
                    error("修改失败")
                }
            })
        }
        //删除客户
        function del(array) {
            for(var i=0;i<array.length;i++){
                req("/customer/delete",{customerid:array[i]},function (d) {
                    if(d.status){
                        success("删除成功")
                    }else if(d.status===0){
                        error("删除失败")
                    }
                })
            }
        }
        //保存客户
        function add() {
            var obj={name:$("#name").val(),
                mobile:$("#mobile").val(),
                origin_type:$("#origin_type").val(),
                shopname:$("#shopname").val(),
                shopaddress:$("#shopaddress").val(),
                kfnumber:$("#kfnumber").val(),
                type:$("#type").val()
            }

            if(obj.name&&obj.mobile&&obj.shopaddress){
                req("/customer/create",obj,function (d) {
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
        //添加设备
        device()
        function device() {
            req("/device/getDevice",{limit:1000,offset:0},function (result) {
                console.log(result);
                for(var i=0,j=result.length;i<j;i++){
                    if(i==0){
                        $("#device").append('<option value='+result[i].id+'selected ="selected" >'+result[i].deviceid+'</option>')
                    }else{
                        $("#device").append('<option value='+result[i].id+'>'+result[i].deviceid+'</option>')
                    }
                }
            })
        }
        $("#addDevice").click(function () {
            addDevice(sessionStorage.cus);
        })
        function addDevice(customerid) {
            req("/device/update",{id:$("#device").val(),customerid:customerid},function (d) {
                if(d.status){
                    success("添加设备成功")
                }else if(d.status===0){
                    error("添加设备失败")
                }
            })
        }
        function getCus(customerid) {
            req("/device/getCus",{customerid:customerid},function (d) {
                var html = [];
                $.each(d, function (key, value) {
                    html.push('<p><span>设备id:'+value.deviceid+'</span><span>设备序列号:'+value.serial +'</span></p>')
                    // html.push('<p><b>' + "设备id" + ':</b> ' + value.deviceid + ';<b>' + "设备序列号" + ':</b> ' + value.serial + '</p>');
                })
                if(d.length==0){
                    $("#devices").html("<p>该商户还没有设备!</p>");
                }else{
                    $("#devices").empty();
                    $("#devices").html(html.join(''));
                }
            })
        }
    })
}else{
    location.href=location.origin+"/arpt-official/html/login.html"
}
