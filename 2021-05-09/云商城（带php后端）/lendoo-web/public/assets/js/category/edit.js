$(function () { 
  $('select').select2({
  });
  $('form').submit(function (e) {
    if ($('#avatar').val() == '') {
      alert('请上传分类图');
      e.preventDefault();
    }
  });

});
