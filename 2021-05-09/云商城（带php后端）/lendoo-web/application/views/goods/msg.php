<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <ol class="breadcrumb">
      <li><a href="../dashboard/index"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li class="active">商品管理</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">操作提示</h3>
                <div class="box-tools pull-right">
                <a class="btn btn-sm btn-primary" href="index">返回列表</a>
                </div><!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              <div class="box-body">
                <div class="alert alert-<?=$level?> alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h4><i class="icon fa fa-<?=$level?>"></i> <?=$msg?></h4>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" id="goBack" class="btn btn-primary">返回</button>
              </div>
              <script type="text/javascript">
                $('#goBack').click(function () {
                  window.location = '<?=$redirect?>';
                });
              </script>
              <!-- /.box-footer -->
          </div>
  </section>
  <!-- /.content -->
</div>
<script>
  $(function () { 
    $("[data-toggle='popover']").popover();
  });
</script>