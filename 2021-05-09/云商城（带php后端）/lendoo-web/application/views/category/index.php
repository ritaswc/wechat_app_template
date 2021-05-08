<!-- 引入JavaScript -->
<script src="/bower_components/bs-confirmation/bootstrap-confirmation.js"></script>
<!--引入CSS-->
<link rel="stylesheet" type="text/css" href="/assets/css/global.css">

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <ol class="breadcrumb">
      <li><a href="../dashboard/index"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li class="active">分类管理</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">分类</h3>
                <div class="box-tools pull-right">
                <a class="btn btn-sm btn-primary" href="add">添加</a>
                </div><!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
              <?php foreach($categories as $category):?>
              <table class="table table-hover table-striped table-bordered">
                <thead>
                  <tr>
                    <!-- 读取大类名称 -->
                    <th style="width: 60%;"><?=$category->get('title')?></th>
                    <th><a type="button" class="btn btn-primary" href="add?objectId=<?=$category->get('objectId')?>">添加</a></th>
                    <th><a type="button" class="btn btn-info" href="edit?objectId=<?=$category->get('objectId')?>">修改</a></th>
                    <th><a type="button" class="btn btn-danger delete<?=$category->get('isLock') == true ? ' disabled' : ''?>" href="delete?objectId=<?=$category->get('objectId')?>">删除</a></th>

                  </tr>
                </thead>
                <tbody>
                  <!-- 遍历子类 -->
                  <?php foreach ($category->children as $child):?>
                  <tr>
                    <td>
                      <div class="col-xs-offset-2"><?=$child->get('title')?></div>
                    </td>
                    <td>
                      <a type="button" class="btn btn-primary" href="add?objectId=<?=$child->get('objectId')?>">添加</a>
                    </td>
                    <td>
                      <a type="button" class="btn btn-info" href="edit?objectId=<?=$child->get('objectId')?>">修改</a>
                    </td>
                    <td>
                      <a type="button" class="btn btn-danger delete<?=$child->get('isLock') == true ? ' disabled' : ''?>" href="delete?objectId=<?=$child->get('objectId')?>">删除</a>
                    </td>
                  </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
              <?php endforeach;?>
              <script type="text/javascript">
                $('.delete').confirmation({
                  onConfirm: function() { },
                  onCancel: function() { },
                  href: function (e) {
                    return $(e).attr('href');
                  },
                  title: '确定删除吗？',
                  btnOkClass: 'btn btn-sm btn-danger btn-margin',
                  btnCancelClass: 'btn btn-sm btn-default btn-margin',
                  btnOkLabel: '删除',
                  btnCancelLabel: '取消',
                  placement: 'bottom'
                })
              </script>
              <!-- /.box-body -->
              <div class="box-footer">
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
  </section>
  <!-- /.content -->
</div>
<script>
  $(function () { 
    $("[data-toggle='popover']").popover();
  });
</script>