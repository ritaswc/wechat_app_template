<?php
defined('YII_ENV') or exit('Access Denied');
$this->title = '编辑角色';
$urlManager = Yii::$app->urlManager;
?>

<style>
    body {
        font-family: Menlo, Consolas, monospace;
        color: #444;
    }

    .item {
        cursor: pointer;
    }

    .bold {
        font-weight: bold;
        margin-bottom: 0;
    }

    ul {
        padding-left: 1em;
        line-height: 1.5em;
        list-style-type: dot;
    }
</style>

<script type="text/x-template" id="item-template">
    <div>
        <ul :class="{bold: isFolder}" style="margin-bottom: 0;">
            <label style="margin-bottom: 0;">
                <input :id="'ck' + model.id" type="checkbox" v-bind:checked="model.show" @click="select">
                {{ model.name }}
            </label>
            <span @click="toggle" v-if="isFolder">[{{ open ? '-' : '+' }}]</span>
        </ul>

        <ul style="margin-bottom: 0;margin-left: 10px;" v-show="open" v-if="isFolder">
            <item
                    class="item col-10"
                    v-for="(model, index) in model.children"
                    :key="index"
                    :model="model">
            </item>
        </ul>
    </div>
</script>

<div class="panel mb-3">
    <div class="panel-header"><?= $this->title ?></div>
    <div class="panel-body" id="app">
        <form method="post">
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">角色名称</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control role-name" value="<?= $edit->name ?>" name="nickname">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label required">描述</label>
                </div>
                <div class="col-sm-6">
                    <input class="form-control description" value="<?= $edit->description ?>" name="username">
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                    <label class="col-form-label">权限</label>
                </div>
                <div class="col-sm-10">
                    <item class="item" v-for="item in treeData" :key="item.id" :model="item"></item>
                </div>
            </div>
            <div class="form-group row">
                <div class="form-group-label col-sm-2 text-right">
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-primary" onclick="store(this)" href="javascript:">保存</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // define the item component
    Vue.component('item', {
        template: '#item-template',
        props: {
            model: Object,
        },
        data: function () {
            return {
                open: false,
                status: '',
            }
        },
        computed: {
            isFolder: function () {
                return this.model.children && this.model.children.length
            }
        },
        methods:
            {
                toggle: function () {
                    if (this.isFolder) {
                        this.open = !this.open
                    }
                },
                select: function () {
                    this.status = !this.model.show;
                    Vue.set(this.model, 'show', this.status)

                    if (this.isFolder) {
                        this.checked(this.model)
                    }

                    if (this.$parent.model) {
                        this.statisticsModel(this.$parent)
                    }
                },
                checked: function (model) {
                    for (var i in model.children) {
                        Vue.set(model.children[i], 'show', this.status)
                        var name = 'ck' + model.id;
                        var ck = document.querySelector('input[id= ' + name + ' ]');
                        ck.indeterminate = false;
                        if (model.children[i].children) {
                            this.checked(model.children[i])
                        }
                    }
                },
                statisticsModel: function (parent) {
                    var count = parent.$children.length
                    var newCount = 0
                    var status = 0;
                    for (var i in parent.$children) {
                        if (parent.$children[i].model.children) {
                            var name = 'ck' + parent.$children[i].model.id;
                            var ck = document.querySelector('input[id= ' + name + ' ]');

                            if (ck.indeterminate === true) {
                                status = 1;
                            }
                        }
                        newCount = parent.$children[i].model.show ? newCount + 1 : newCount

                        var name = 'ck' + parent.model.id;
                        var ck = document.querySelector('input[id= ' + name + ' ]');

                        if (count === newCount) {
                            Vue.set(parent.model, 'show', true)
                            if (status) {
                                ck.indeterminate = true
                            } else {
                                ck.indeterminate = false
                            }
                        } else if (newCount > 0 && newCount < count) {
                            Vue.set(parent.model, 'show', true)
                            ck.indeterminate = true
                        } else {
                            ck.indeterminate = false;
                            Vue.set(parent.model, 'show', false)
                        }
                    }

                    if (parent.model.pid !== 0) {
                        this.statisticsModel(parent.$parent)
                    }

                    return parent
                }
            }
    });

    var app = new Vue({
        el: '#app',
        data: {
            treeData: <?=$permissions?>,
            status: false,
            id: 0,
        }
    });

    isIndeterminate(app.treeData)

    //添加未全选样式
    function isIndeterminate(tree) {
        for (var i in tree) {
            app.status = tree[i].pid === 0 ? false : app.status
            if (tree[i].pid === 0) {
                console.log(7777)
            }

            if (tree[i].children) {
                var data = isIndeterminate(tree[i].children)
                var count = data.length
                var newCount = 0
                var children = false

                for (var j in data) {
                    if (data[j].children) {
                        children = true
                    }
                    newCount = data[j].show ? newCount + 1 : newCount
                    var name = 'ck' + data[j].pid;
                    var ck = document.querySelector('input[id= ' + name + ' ]');
                }

                if (newCount > 0 && newCount < count) {
                    app.status = true
                    console.log('status-1=>' + app.status)
                } else if (app.status && children) {
                    app.status = true
                } else {
                    app.status = false
                }

                if (app.status) {
                    ck.indeterminate = true;
                }

                console.log(app.status)
                console.log(count)
                console.log(newCount)
                console.log('========')
            }
        }

        return tree
    }

    function getRoleByUser(list) {
        var role = [];
        for (var i in list) {

            if (list[i].show === true && list[i].route !== '') {
                role.push(list[i].route)
            }
            if (list[i].children) {
                role = role.concat(getRoleByUser(list[i].children))
            }
        }

        return role;
    }

    function store(t) {
        var role = getRoleByUser(app.treeData);
        var btn = $(t);
        btn.btnLoading();
        $.ajax({
            url: '<?= $urlManager->createUrl(['mch/permission/role/update', 'id' => $edit->id]) ?>',
            type: 'POST',
            data: {
                role: JSON.stringify(role),
                name: $('.role-name').val(),
                description: $('.description').val(),
                _csrf: _csrf
            },
            success: function (res) {
                $.myAlert({
                    content: res.msg,
                    confirm: function () {
                        if (res.code == 0) {
                            location.reload();
                        }
                    }
                })
            },
            complete: function (res) {
                btn.btnReset();
            }
        })
    }
</script>
