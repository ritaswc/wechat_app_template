function getAllPages() {
  let stack = getCurrentPages();
  // 第一个入栈
  stack.forEach((item) => {
    if (myStatcks.length === 0) {
      myStatcks = stack.concat();
    } else {
      for (let i = 0, _len = myStatcks.length; i < _len; i++) {
        let ele = myStatcks[i];
        if (ele.__route__ === item.__route__) {
          myStatcks[i] = item;
          break;
        } else {
          myStatcks.push(item);
          break;
        }
      }
    }
  });
  // }
  return myStatcks;
}

module.exports = function $invoke(router, method, args) {
  let pages = getAllPages();
  let targetPage;
  for (let index = 0, _len = pages.length; index < _len; index++) {
    if (pages[index].__route__ === router) {
      targetPage = pages[index];
      break;
    }
  }
  targetPage[method].call(targetPage, ...args);
}