let myStatcks = [];

module.exports = function getAllPages() {
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