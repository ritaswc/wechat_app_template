/**
 * Created by Administrator on 2016/12/17.
 */
 //模拟id的增加
    var ids=0;
//创建存储数据的数据库类
var ueditors={
    id:[],
    List_name:[],
    Content:[],
    push:function(s){
        if(s.id!=-1){
            this.List_name[s.id-1]=s.List_name;
            this.Content[s.id-1]=s.Content;
        }
        else{
            this.id.push(ids+1);
            ids=ids+1;
            this.List_name.push(s.List_name);
            this.Content.push(s.Content);
        }
    }
}
ueditors.push({id:-1,List_name:"入门使用",Content:"写好题目内容保存好"});
exports.returnDatas_List=function(req, res){
    res.send(200, {
        id:ueditors.id,
        List_name: ueditors.List_name
    })
}
exports.returnDatas=function(req, res){
    var i=req.body.id-1;
    res.send(200, {
        id:ueditors.id[i],
        List_name:ueditors.List_name[i],
        Content: ueditors.Content[i]
    })
}
exports.saveImage=function(req, res, next)
{
    ueditor(path.join(__dirname, 'public'), function (req, res, next) {
        // ueditor 客户发起上传图片请求
        if (req.query.action === 'uploadimage') {
            var foo = req.ueditor;

            var imgname = req.ueditor.filename;

            var img_url = '/images/ueditor/';
            res.ue_up(img_url); //你只要输入要保存的地址 。保存操作交给ueditor来做
            res.setHeader('Content-Type', 'text/html');//IE8下载需要设置返回头尾text/html 不然json返回文件会被直接下载打开
        }
        //  客户端发起图片列表请求
        else if (req.query.action === 'listimage') {
            var dir_url = '/images/ueditor/';
            res.ue_list(dir_url); // 客户端会列出 dir_url 目录下的所有图片
        }
        // 客户端发起其它请求
        else {
            // console.log('config.json')
            res.setHeader('Content-Type', 'application/json');
            res.redirect('/ueditor/nodejs/config.json');
        }
    }(req, res, next));
}
//参数 Content:{id:,List_name:,Content:}
exports.saveDatas=function(req, res){
    ueditors.push(req.body.data);
    res.send(200, {
        id:ueditors.id,
        List_name: ueditors.List_name
    })
}