
let timeout;

// 拿到当前页面对象
const getPage = () => {
  const pages = getCurrentPages()
  const curPage = pages[pages.length - 1]
  return curPage
};

//隐藏弹出框
const hide = () => {
  getPage().setData({
      '__msg__': {
        msgStatus: false,
        msgType: '',
        msgText: ''
      }
  });
};

//显示弹出框, 不隐藏
const wait = (text) => {
  getPage().setData({
      __msg__: {
        msgStatus: true,
        msgType: 'wait',
        msgText: text
      }
  });
}

//显示弹出框，默认1.5秒隐藏
const show = (text, duration = 1500, cb = function(){}) => {
  if(timeout){
    clearTimeout(timeout);
  }

  getPage().setData({
      '__msg__': {
        msgStatus: true,
        msgText: text,
        msgType: 'show',
        msgDuration: duration
      }
  });

  timeout = setTimeout(()=>{
    hide();
    cb();
  }, duration);
}


function Msg(){
  const curPage = getPage();

  curPage.Msg = show;
  curPage.Msg.show = show;
  curPage.Msg.wait = wait;
  curPage.Msg.hide = hide;
  return curPage.Msg;
}



//export default Msg = new msg();
module.exports = {
    Msg
}

