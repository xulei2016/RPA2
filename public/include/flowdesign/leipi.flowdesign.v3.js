/*
项目：雷劈网流程设计器
官网：http://flowdesign.leipi.org
Q 群：143263697
基本协议：apache2.0

88888888888  88                             ad88  88                ad88888ba   8888888888   
88           ""                            d8"    88               d8"     "88  88           
88                                         88     88               8P       88  88  ____     
88aaaaa      88  8b,dPPYba,   ,adPPYba,  MM88MMM  88  8b       d8  Y8,    ,d88  88a8PPPP8b,  
88"""""      88  88P'   "Y8  a8P_____88    88     88  `8b     d8'   "PPPPPP"88  PP"     `8b  
88           88  88          8PP"""""""    88     88   `8b   d8'            8P           d8  
88           88  88          "8b,   ,aa    88     88    `8b,d8'    8b,    a8P   Y8a     a8P  
88           88  88           `"Ybbd8"'    88     88      Y88'     `"Y8888P'     "Y88888P"   
                                                          d8'                                
2014-3-15 Firefly95、xinG  
*/
(function ($) {
    var defaults = {
        nodeData: {},//步骤节点数据
        //nodeUrl:'',//步骤节点数据
        fnRepeat: function () {
            alert("步骤连接重复");
        },
        fnClick: function () {
            alert("单击");
        },
        fnDbClick: function () {
            alert("双击");
        },
        canvasMenus: {
            "one": function (t) { alert('画面右键') }
        },
        nodeMenus: {
            "one": function (t) { alert('步骤右键') }
        },
        onlyShow:false,
        /*右键菜单样式*/
        menuStyle: {
            border: '1px solid #5a6377',
            minWidth: '150px',
            padding: '5px 0'
        },
        itemStyle: {
            fontFamily: 'verdana',
            color: '#333',
            border: '0',
            /*borderLeft:'5px solid #fff',*/
            padding: '5px 40px 5px 20px'
        },
        itemHoverStyle: {
            border: '0',
            /*borderLeft:'5px solid #49afcd',*/
            color: '#fff',
            backgroundColor: '#5a6377'
        },
        mtAfterDrop: function (params) {
            //alert('连接成功后调用');
            //alert("连接："+params.sourceId +" -> "+ params.targetId);
        },
        //这是连接线路的绘画样式
        connectorPaintStyle: {
            lineWidth: 3,
            strokeStyle: "#49afcd",
            joinstyle: "round"
        },
        //鼠标经过样式
        connectorHoverStyle: {
            lineWidth: 3,
            strokeStyle: "#da4f49"
        }

    };/*defaults end*/

    var initEndPoints = function () {
        $(".node-flag").each(function (i, e) {
            var p = $(e).parent();
            jsPlumb.makeSource($(e), {
                parent: p,
                anchor: "Continuous",
                endpoint: ["Dot", { radius: 1 }],
                connector: ["Flowchart", { stub: [5, 5] }],
                connectorStyle: defaults.connectorPaintStyle,
                hoverPaintStyle: defaults.connectorHoverStyle,
                dragOptions: {},
                maxConnections: -1
            });
        });
    }

    /*设置隐藏域保存关系信息*/
    var aConnections = [];
    var setConnections = function (conn, remove) {
        if (!remove) aConnections.push(conn);
        else {
            var idx = -1;
            for (var i = 0; i < aConnections.length; i++) {
                if (aConnections[i] == conn) {
                    idx = i; break;
                }
            }
            if (idx != -1) aConnections.splice(idx, 1);
        }
        if (aConnections.length > 0) {
            var s = "";
            for (var j = 0; j < aConnections.length; j++) {
                var from = $('#' + aConnections[j].sourceId).attr('node_id');
                var target = $('#' + aConnections[j].targetId).attr('node_id');
                s = s + "<input type='hidden' value=\"" + from + "," + target + "\">";
            }
            $('#leipi_node_info').html(s);
        } else {
            $('#leipi_node_info').html('');
        }
        jsPlumb.repaintEverything();//重画
    };

    /*Flowdesign 命名纯粹为了美观，而不是 formDesign */
    $.fn.Flowdesign = function (options) {
        var _canvas = $(this);
        //右键步骤的步骤号
        _canvas.append('<input type="hidden" id="leipi_active_id" value="0"/><input type="hidden" id="leipi_copy_id" value="0"/>');
        _canvas.append('<div id="leipi_node_info"></div>');


        /*配置*/
        $.each(options, function (i, val) {
            if (typeof val == 'object' && defaults[i])
                $.extend(defaults[i], val);
            else
                defaults[i] = val;
        });
        /*画布右键绑定*/
        var contextmenu = {
            bindings: defaults.canvasMenus,
            menuStyle: defaults.menuStyle,
            itemStyle: defaults.itemStyle,
            itemHoverStyle: defaults.itemHoverStyle
        }
        $(this).contextMenu('canvasMenu', contextmenu);

        jsPlumb.importDefaults({
            DragOptions: { cursor: 'pointer' },
            EndpointStyle: { fillStyle: '#225588' },
            Endpoint: ["Dot", { radius: 1 }],
            ConnectionOverlays: [
                ["Arrow", { location: 1 }],
                ["Label", {
                    location: 0.1,
                    id: "label",
                    cssClass: "aLabel"
                }]
            ],
            Anchor: 'Continuous',
            ConnectorZIndex: 5,
            HoverPaintStyle: defaults.connectorHoverStyle
        });
        if ($.browser.msie && $.browser.version < '9.0') { //ie9以下，用VML画图
            jsPlumb.setRenderMode(jsPlumb.VML);
        } else { //其他浏览器用SVG
            jsPlumb.setRenderMode(jsPlumb.SVG);
        }


        //初始化原步骤
        var lastNodeId = 0;
        var nodeData = defaults.nodeData;
        if (nodeData.list) {
            $.each(nodeData.list, function (i, row) {
                var nodeDiv = document.createElement('div');
                var nodeId = "window" + row.id, badge = 'badge-inverse', icon = 'icon-star';
                if (lastNodeId == 0)//第一步
                {
                    badge = 'badge-info';
                    icon = 'icon-play';
                }
                if (row.icon) {
                    icon = row.icon;
                }
                $(nodeDiv).attr("id", nodeId)
                    .attr("style", row.style)
                    .attr("node_to", row.node_to)
                    .attr("node_id", row.id)
                    .addClass("node-step btn btn-small")
                    .html('<span class="node-flag badge ' + badge + '"><i class="' + icon + ' icon-white"></i></span>&nbsp;' + row.node_name)
                    .mousedown(function (e) {
                        if (e.which == 3) { //右键绑定
                            _canvas.find('#leipi_active_id').val(row.id);
                            contextmenu.bindings = defaults.nodeMenus
                            $(this).contextMenu('nodeMenu', contextmenu);
                        }
                    });
                _canvas.append(nodeDiv);
                //索引变量
                lastNodeId = row.id;
            });//each
        }
        var timeout = null;
        //点击或双击事件,这里进行了一个单击事件延迟，因为同时绑定了双击事件
        $(".node-step").live('click', function () {
            //激活
            _canvas.find('#leipi_active_id').val($(this).attr("node_id")),
                clearTimeout(timeout);
            var obj = this;
            timeout = setTimeout(defaults.fnClick, 300);
        }).live('dblclick', function () {
            clearTimeout(timeout);
            defaults.fnDbClick();
        });

        console.log(defaults);
        //使之可拖动
        if(!defaults.onlyShow) {
            jsPlumb.draggable(jsPlumb.getSelector(".node-step"), { containment: "parent" });
            //绑定添加连接操作。画线-input text值  拒绝重复连接
            jsPlumb.bind("jsPlumbConnection", function (info) {
                setConnections(info.connection)
            });
            //绑定删除connection事件
            jsPlumb.bind("jsPlumbConnectionDetached", function (info) {
                setConnections(info.connection, true);
            });
            //绑定删除确认操作
            jsPlumb.bind("click", function (c) {
                if (confirm("你确定取消连接吗?"))
                    jsPlumb.detach(c);
            });
        }
        initEndPoints();



        //连接成功回调函数
        function mtAfterDrop(params) {
            //console.log(params)
            defaults.mtAfterDrop({ sourceId: $("#" + params.sourceId).attr('node_id'), targetId: $("#" + params.targetId).attr('node_id') });

        }

        jsPlumb.makeTarget(jsPlumb.getSelector(".node-step"), {
            dropOptions: { hoverClass: "hover", activeClass: "active" },
            anchor: "Continuous",
            maxConnections: -1,
            endpoint: ["Dot", { radius: 1 }],
            paintStyle: { fillStyle: "#ec912a", radius: 1 },
            hoverPaintStyle: this.connectorHoverStyle,
            beforeDrop: function (params) {
                if (params.sourceId == params.targetId) return false;/*不能链接自己*/
                var j = 0;
                $('#leipi_node_info').find('input').each(function (i) {
                    var str = $('#' + params.sourceId).attr('node_id') + ',' + $('#' + params.targetId).attr('node_id');
                    if (str == $(this).val()) {
                        j++;
                        return;
                    }
                })
                if (j > 0) {
                    defaults.fnRepeat();
                    return false;
                } else {
                    mtAfterDrop(params);
                    return true;
                }
            }
        });


        //reset  start
        var _canvas_design = function () {
            //连接关联的步骤
            $('.node-step').each(function (i) {
                var sourceId = $(this).attr('node_id');
                //var nodeId = "window"+id;
                var prcsto = $(this).attr('node_to');
                var toArr = prcsto.split(",");
                var nodeData = defaults.nodeData;
                $.each(toArr, function (j, targetId) {

                    if (targetId != '' && targetId != 0) {
                        //检查 source 和 target是否存在
                        var is_source = false, is_target = false;
                        $.each(nodeData.list, function (i, row) {
                            if (row.id == sourceId) {
                                is_source = true;
                            } else if (row.id == targetId) {
                                is_target = true;
                            }
                            if (is_source && is_target)
                                return true;
                        });

                        if (is_source && is_target) {
                            jsPlumb.connect({
                                source: "window" + sourceId,
                                target: "window" + targetId
                                /* ,labelStyle : { cssClass:"component label" }
                                 ,label : id +" - "+ n*/
                            });
                            return;
                        }
                    }
                })
            });

        }//_canvas_design end reset 
        _canvas_design();
        //-----外部调用----------------------


        var Flowdesign = {

            addNode: function (row) {

                if (row.id <= 0) {
                    return false;
                }
                var nodeDiv = document.createElement('div');
                var nodeId = "window" + row.id, badge = 'badge-inverse', icon = 'icon-star';

                if (row.icon) {
                    icon = row.icon;
                }
                $(nodeDiv).attr("id", nodeId)
                    .attr("style", row.style)
                    .attr("node_to", row.node_to)
                    .attr("node_id", row.id)
                    .addClass("node-step btn btn-small")
                    .html('<span class="node-flag badge ' + badge + '"><i class="' + icon + ' icon-white"></i></span>&nbsp;' + row.node_name)
                    .mousedown(function (e) {
                        if (e.which == 3) { //右键绑定
                            _canvas.find('#leipi_active_id').val(row.id);
                            contextmenu.bindings = defaults.nodeMenus
                            $(this).contextMenu('nodeMenu', contextmenu);
                        }
                    });

                _canvas.append(nodeDiv);
                //使之可拖动 和 连线
                jsPlumb.draggable(jsPlumb.getSelector(".node-step"));
                initEndPoints();
                //使可以连接线
                jsPlumb.makeTarget(jsPlumb.getSelector(".node-step"), {
                    dropOptions: { hoverClass: "hover", activeClass: "active" },
                    anchor: "Continuous",
                    maxConnections: -1,
                    endpoint: ["Dot", { radius: 1 }],
                    paintStyle: { fillStyle: "#ec912a", radius: 1 },
                    hoverPaintStyle: this.connectorHoverStyle,
                    beforeDrop: function (params) {
                        var j = 0;
                        $('#leipi_node_info').find('input').each(function (i) {
                            var str = $('#' + params.sourceId).attr('node_id') + ',' + $('#' + params.targetId).attr('node_id');
                            if (str == $(this).val()) {
                                j++;
                                return;
                            }
                        })
                        if (j > 0) {
                            defaults.fnRepeat();
                            return false;
                        } else {
                            return true;
                        }
                    }
                });
                return true;

            },
            delNode: function (activeId) {
                if (activeId <= 0) return false;

                $("#window" + activeId).remove();
                return true;
            },
            getActiveId: function () {
                return _canvas.find("#leipi_active_id").val();
            },
            copy: function (active_id) {
                if (!active_id)
                    active_id = _canvas.find("#leipi_active_id").val();

                _canvas.find("#leipi_copy_id").val(active_id);
                return true;
            },
            paste: function () {
                return _canvas.find("#leipi_copy_id").val();
            },
            getNodeInfo: function () {
                try {
                    /*连接关系*/
                    var aNodeData = {};
                    $("#leipi_node_info input[type=hidden]").each(function (i) {
                        var nodeVal = $(this).val().split(",");
                        if (nodeVal.length == 2) {
                            if (!aNodeData[nodeVal[0]]) {
                                aNodeData[nodeVal[0]] = { "top": 0, "left": 0, "node_to": [] };
                            }
                            aNodeData[nodeVal[0]]["node_to"].push(nodeVal[1]);
                        }
                    })
                    /*位置*/
                    _canvas.find("div.node-step").each(function (i) { //生成Json字符串，发送到服务器解析
                        if ($(this).attr('id')) {
                            var pId = $(this).attr('node_id');
                            var pLeft = parseInt($(this).css('left'));
                            var pTop = parseInt($(this).css('top'));
                            if (!aNodeData[pId]) {
                                aNodeData[pId] = { "top": 0, "left": 0, "node_to": [] };
                            }
                            aNodeData[pId]["top"] = pTop;
                            aNodeData[pId]["left"] = pLeft;

                        }
                    })
                    return JSON.stringify(aNodeData);
                } catch (e) {
                    return '';
                }

            },
            clear: function () {
                try {

                    jsPlumb.detachEveryConnection();
                    jsPlumb.deleteEveryEndpoint();
                    $('#leipi_node_info').html('');
                    jsPlumb.repaintEverything();
                    return true;
                } catch (e) {
                    return false;
                }
            }, refresh: function () {
                try {
                    //jsPlumb.reset();
                    this.clear();
                    _canvas_design();
                    return true;
                } catch (e) {
                    return false;
                }
            }
        };


        return Flowdesign;


    }//$.fn
})(jQuery);