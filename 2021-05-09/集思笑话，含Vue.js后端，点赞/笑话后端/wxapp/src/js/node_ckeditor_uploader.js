/* eslint-disable */

(function (CKEDITOR) {
/*
    options: {
        actionUrl: String,    请求上传路径
        responseId: String,   响应HTML元素的ID
        responseAttr:String   响应元素中存储响应数据的特性
    }
*/
CKEDITOR.extendCKEDITOR = function (options) {
    // 对话框渲染
    CKEDITOR.on('dialogDefinition', function(evt){
        var name = evt.data.name,
            def,
            infoContent,
            listenId,
            iframeName,
            showEvent,
            okEvent, 
            cancelEvent;

        // 重设"image"对话框
        if (name === 'image') {
            def = evt.data.definition;
            showEvent = def.onShow;
            cancelEvent = def.onCancel;

            def.onShow = function () {
                if (typeof showEvent === 'function') {
                    showEvent.call(this);
                }
                
                var uploadButton = this.getContentElement('info', 'uploadButton').getElement().$;
                var loadingButton = this.getContentElement('info', 'loading').getElement().$;
                uploadButton.style.display = 'inline-block';
                loadingButton.style.display = 'none';
            };

            def.onCancel = function () {
                if (typeof cancelEvent === 'function') {
                    cancelEvent.call(this);
                }
                
                // 如果侦听被终止，清除定时器
                clearTimeout(listenId);
                listenId = null;
            };

            infoContent = def.getContents('info');
/*
            infoContent.add({
                id: 'loading',
                type: 'button',
                label: '...',
                style: 'margin-top:14px;display:none;',
                icon: '/f/new.jpg'
            }, 'browse');
*/
            infoContent.add({
                id: 'loading',
                type: 'html',
                style: 'display:none;margin-top:4px;overflow:hidden;width:30px;height:40px;',
                html: '<img src="/img/loading.gif" alt="fjpg" />'
            }, 'browse');

            // 添加装饰按钮
            infoContent.add({
                id: 'uploadButton',
                type: 'button',
                label: '浏览文件...',
                style: 'margin-top:14px;',
                onClick: function () {
                    var upload, iframe, fileInput;
                    // 文件上传控件所在容器
                    upload = this.getDialog().getContentElement('info', 'upload').getElement().$;
                    // 文件上传控件所在框体
                    iframe = upload.getElementsByTagName('iframe')[0];
                    // 设置文件上传控件所在框体的name
                    iframeName = iframe.name = iframe.id;
                    // 文件上传控件
                    fileInput = frames[iframeName].document.getElementsByTagName('input')[0];
                    // 激活文件上传控件
                    fileInput.click();
                }
            }, 'loading');

            // 添加上传控件
            infoContent.add({
                id: 'upload',
                type: 'file',
                style:'display:none',
                action: options.actionUrl,
                onChange: function () { 
                    var responseElem, urlInput, uploadButton, loadingButton;

                    // 侦听服务器响应
                    function listen () {
                        responseElem = frames[iframeName].document.getElementById(options.responseId);
                        uploadButton.style.display = 'none';
                        loadingButton.style.display = 'inline-block';
                        // 服务器响应，发回包含src信息的div元素
                        if (responseElem !== null && typeof responseElem === 'object') {
                            urlInput.setValue(responseElem.getAttribute(options.responseAttr));
                            loadingButton.style.display = 'none';
                            return;
                        } 
                        listenId = setTimeout(listen, 100);
                    }

                    uploadButton = this.getDialog().getContentElement('info', 'uploadButton').getElement().$;
                    loadingButton = this.getDialog().getContentElement('info', 'loading').getElement().$;
                    // URL输入框
                    urlInput = this.getDialog().getContentElement('info', 'txtUrl');
                    this.submit();
                    listen();
                }
            }, 'browse');

            infoContent.remove('browse');
        }
    });
}
}(CKEDITOR));

/* eslint-enable */
