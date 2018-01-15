/**
 * Created by Aber on 17/3/20.
 */
// 表格操作
if(checklogin()){
    $(function () {
        sessionStorage.removeItem("search")
        window.operateEvents = {
            'click .remove': function (e, value, row, index) {
                $table.bootstrapTable('remove', {
                    field: 'deviceid',
                    values: [row.deviceid]
                });
                del([row.deviceid]) //删除
            },
            'click .w':function (e, value, row, index) {
                row.logistics=2;
                change(row)
                setTimeout(function () {
                    location.reload()
                }, 500);
            },
            'click .p':function (e, value, row, index) {
                row.logistics=3;
                change(row)
                setTimeout(function () {
                    location.reload()
                }, 500);
            },
            'click .c':function (e, value, row, index) {
                row.logistics=4;
                change(row)
                setTimeout(function () {
                    location.reload()
                }, 500);
            }
        };
        function operateFormatter(value, row, index) {
            if(sessionStorage.search=="w"){
                return [
                    '<a class="w" href="javascript:void(0)" title="Remove">',
                    '生产',
                    '</a>'
                ].join('');
            }
            else if(sessionStorage.search=="p"){
                return [
                    '<a class="p" href="javascript:void(0)" title="Remove">',
                    '出仓',
                    '</a>'
                ].join('');
            }
            else if(sessionStorage.search=="c"){
                return [
                    '<a class="c" href="javascript:void(0)" title="Remove">',
                    '送货',
                    '</a>'
                ].join('');
            }
            else if(sessionStorage.search=="s"){
                return [
                    '<a class="remove" href="javascript:void(0)" title="Remove">',
                    '删除',
                    '</a>'
                ].join('');
            }
            else{
                return [
                    '<a class="remove" href="javascript:void(0)" title="Remove">',
                    '<i class="glyphicon glyphicon-remove"></i>',
                    '</a>'
                ].join('');
            }
        }
        var $table = $('#tables'),
            $remove = $('#remove'),
            selections = [];
        function clockon(s) {
            var now = new Date(s);
            var year = now.getFullYear(); //getFullYear getYear
            var month = now.getMonth();
            var date = now.getDate();
            var day = now.getDay();
            var hour = now.getHours();
            var minu = now.getMinutes();
            var sec = now.getSeconds();
            var week;
            month = month + 1;
            if (month < 10) month = "0" + month;
            if (date < 10) date = "0" + date;
            if (hour < 10) hour = "0" + hour;
            if (minu < 10) minu = "0" + minu;
            if (sec < 10) sec = "0" + sec;
            var arr_week = new Array("星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六");
            week = arr_week[day];
            var time = "";
            time = year + "年" + month + "月" + date + "日" + " " + hour + ":" + minu + ":" + sec + " " + week;
            return time
        }
// 列按钮操作
        //$id, $offset, $limit, $search, $order, $ordername
        function init() {
            //初始化Table
            req("/device/listPage",{limit:10,offset:0},function (result) {
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
                    uniqueId: "deviceid",
                    pageList: [5, 10, 20, 50,100],//分页步进值
                    sidepagination: "client",
                    showToggle: false,
                    showColumns: true,
                    showRefresh: false,
                    striped: true,
                    columns: [
                        {
                            field: 'state',
                            checkbox: true
                        },
                        {
                            title: '设备id',//标题
                            field: 'deviceid'//域值
                        },
                        {
                            title: '商家id',//标题
                            field: 'customerid'//域值
                            ,editable:{
                                type:'text',
                                title:'绑定的商家id',
                                validate:function(value) {
                                    if ($.trim(value) == '') {
                                        return '不能为空';//修改是数据为空 显示
                                    }
                                }
                            }
                        },
                        {
                            title: "设备序列",//标题
                            field: "serial",//键名
                            // editable:true
                            editable:{
                                type:'text',
                                title:'设备序列',
                                validate:function(value) {
                                    if ($.trim(value) == '') {
                                        return '不能为空';//修改是数据为空 显示
                                    }
                                }
                            }

                        },
                        {
                            title: "设备类型",//标题
                            field: "type",//键名
                            // editable:true
                            editable:{
                                type:'select',
                                title:'设备型号',
                                source:[{value:1,text:"AR整形导师"},{value:2,text:"颜值导师"}],
                                validate:function(value) {
                                    if ($.trim(value) == '') {
                                        return '不能为空';//修改是数据为空 显示
                                    }
                                }
                            }

                        },
                        {
                            title: "物流类型",//标题
                            field: "logistics",//键名
                            // editable:true
                            editable:{
                                type:'select',
                                title:'设备型号',
                                source:[{value:1,text:"未生产"},{value:2,text:"已生产"},{value:3,text:"已出仓"},{value:4,text:"已发送"}],
                                validate:function(value) {
                                    if ($.trim(value) == '') {
                                        return '不能为空';//修改是数据为空 显示
                                    }
                                }
                            }

                        },
                        {
                            title: "出仓日期",//标题
                            field: "outstorage_datetime",//键名
                            // editable:true
                            formatter:function (value,row,inde) {
                                if(value){
                                    return value.substring(0,10);
                                }
                                else{
                                    return value
                                }
                            },
                            editable:{
                                type:'date',
                                title:'出仓日期',
                                pk:1,
                                format: 'yyyy-mm-dd',
                                viewformat: 'yyyy-mm-dd',
                                datepicker: {
                                    firstDay: 1
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
                        if(field=="logistics"){
                            change(row)
                        }else{
                            update(row)
                        }
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
            //         $.get('/device/deatil', function (res) {
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
                    field: 'id',
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
                    return row.id
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
                    html.push('<p><b>' + key + ':</b> ' + value + '</p>');
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
                mobile=$("#mobile").val("")
        })
        $('[data-toggle="tooltip"]').tooltip()

        //修改设备
        function update(object) {
            req("/device/update",object,function (d) {
                if(d.status){
                    success("修改成功")
                }else if(d.status===0){
                    error("修改失败")
                }
            })
        }
        //修改设备
        function change(object) {
            if(sessionStorage.appid==2&&object.logistics!=2){
                error("你的权限只能修改成已生产")
                setTimeout(function () {
                    location.reload();
                },2000)
            }
            else if(sessionStorage.appid==3&&object.logistics!=3){
                error("你的权限只能修改成已出仓")
                setTimeout(function () {
                    location.reload();
                },2000)
            }else{
                req("/device/update",object,function (d) {
                    if(d.status){
                        success("修改成功")
                    }else if(d.status===0){
                        error("修改失败")
                    }
                })
            }
        }
        //删除设备
        function del(array) {
            for(var i=0;i<array.length;i++){
                req("/device/delete",{id:array[i]},function (d) {
                    if(d.status){
                        success("删除成功")
                    }else if(d.status===0){
                        error("删除失败")
                    }
                })
            }
        }
        //保存设备
        function add() {
            var outstorage_datetime=$("#outstorage_datetime").val(),
                type=$("#type").val(),
                logistics=$("#logistics").val(),
                serial=$("#serial").val();
            if(logistics&&serial){
                req("/device/create",{type:type,
                    customerid:sessionStorage.customerid,
                    outstorage_datetime:outstorage_datetime,
                    logistics:logistics,
                    serial:serial},
                    function (d) {
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
        function search(key,value) {
            req("/device/search",{key:key,value:value,startDatetime:"",endDatetime:""},function (d) {
                $("#tables").bootstrapTable("load",d)
            })
        }
        $("#search_w").click(function () {
            sessionStorage.setItem("search","w")
            search("logistics",1)
        })
        $("#search_p").click(function () {
            sessionStorage.setItem("search","p")
            search("logistics",2)
        })
        $("#search_c").click(function () {
            sessionStorage.setItem("search","c")
            search("logistics",3)
        })
        $("#search_s").click(function () {
            sessionStorage.setItem("search","s")
            search("logistics",4)
        })
    })
}else{
    location.href=location.origin+"/arpt-official/html/login.html"
}
