<!--引入CSS-->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/select2/select2.min.css">
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

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
              <h3 class="box-title">添加</h3>
                <div class="box-tools pull-right">
                <a class="btn btn-sm btn-primary" href="index">返回列表</a>
                </div><!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
              <form class="form-horizontal" action="save" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="title" class="col-sm-2 control-label">标题</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="title" id="title" placeholder="请输入分类的标题" value="">
                  </div>
                </div>
                <div class="form-group">
                  <label for="title" class="col-sm-2 control-label">父类</label>
                  <div class="col-sm-8">
                    <select class="form-control select2" style="width: 100%;" name="category">
                      <option value="">顶级分类</option>
                      <?php foreach ($categories as $category):?>
                        <option <?=$category->get('objectId') == $objectId ? 'selected' : '' ?> value="<?=$category->get('objectId')?>">|--<?=$category->get('title')?></option>
                          <?php foreach ($category->children as $child):?>
                            <option <?=$child->get('objectId') == $objectId ? 'selected' : '' ?> value="<?=$child->get('objectId')?>">|--|--<?=$child->get('title')?></option>
                          <?php endforeach;?>
                      <?php endforeach;?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="index" class="col-sm-2 control-label">序号</label>
                  <div class="col-sm-8">
                    <input type="number" class="form-control" name="index" id="index" placeholder="最小最靠前"value="">
                  </div>
                </div>
                <div class="form-group">
                  <label for="avatar" class="col-sm-2 control-label">分类图</label>
                  <div class="col-sm-8">
                    <input type="file" name="avatar" id="avatar">
                  </div>
                </div>
                <div class="form-group">
                  <label for="banner" class="col-sm-2 control-label">横幅图</label>
                  <div class="col-sm-8">
                    <input type="file" name="banner" id="banner">
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" id="submit" class="btn btn-primary">保存</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
  </section>
  <!-- /.content -->
</div>
<script src="/assets/js/category/edit.js"></script>