// import { $isEmpty, } from './helper.js';

function $isEmpty(obj) {
  if (obj == null) return true;
  return Object.keys(obj).length === 0;
}

// eventListener
let _events = {};
// 页面栈
let pageStatcks = [];

let utils = {
  // 得到所有页面的堆栈
  _getAllPages() {
    let stack = getCurrentPages();
    // 第一个入栈
    stack.forEach((item) => {
      if (pageStatcks.length === 0) {
        pageStatcks = stack.concat();
      } else {
        for (let i = 0, _len = pageStatcks.length; i < _len; i++) {
          let ele = pageStatcks[i];
          if (ele.__route__ === item.__route__) {
            pageStatcks[i] = item;
            break;
          } else {
            pageStatcks.push(item);
            break;
          }
        }
      }
    });
    return pageStatcks;
  },

  _getPageEvents(target) {
    let cEvent = null;
    const pageEvent = [ /*'onLoad',*/ 'onShow', 'onReady', 'onHide', 'onUnload', 'onPullDownRefresh', 'onReachBottom', 'onShareAppMessage'];
    for (let key in target) {
      //存在
      if (pageEvent.indexOf(key) > -1) {
        cEvent = cEvent || {};
        cEvent[key] = target[key];
      }
    }
    return cEvent;
  },

  // 执行队列中的方法
  _executeQueue(object, context, args = []) {
    if ($isEmpty(object)) return;

    for (let key in object) {
      object[key].call(context, ...args);
    }
  },

  // 删除 events事件
  /*removeEvents(events) {
    if ($isEmpty(events)) {
      return;
    }
    for (let prop in events) {
      for (let key in _events) {
        if (prop === key) {
          delete _events[key];
          break;
        }
      }
    }
  },*/

  // 即先响应组件本身响应事件，然后再响应混合对象中响应事件。
  compatibleMixin(pageMethods, methods, context) {
    // 两个全是为空
    if ($isEmpty(pageMethods) && $isEmpty(methods)) {
      return;
    }

    let list = Object.keys(pageMethods || {}).concat(Object.keys(methods || {}));
    let keys = list.reduce((pre, item) => {
      pre[item] = item;
      return pre;
    }, {});

    for (let prop in keys) {
      context[prop] = function() {
        if (Object.keys(pageMethods).indexOf(prop) > -1) {
          pageMethods[prop].call(context, ...arguments);
        }
        if (Object.keys(pageMethods).indexOf(prop) > -1) {
          utils._executeQueue(methods[prop], context, arguments);
        }
      };
    }
  },
  // 即如果组件未声明该数据，组件，事件，自定义方法等，那么将混合对象中的选项将注入组件这中。对于组件已声明的选项将不受影响
  defaultMixin(mixins, config, key) {
    let data = {};
    mixins.forEach((item) => {
      let itemKey = ((item.default && item.default[key]) || item[key]) || {};
      for (let prop in itemKey) {
        data[prop] = itemKey[prop];
      }
    });
    return Object.assign({}, data, config[key]);
  },

  // methods mixins
  adapterMixin(mixins, config, style) {
    let queue = {};
    let pageMap = config[style];
    mixins.forEach((item) => {
      // 兼容 两种export导出方式:
      let itemKey = ((item.default && item.default[style]) || item[style]) || {};
      for (let prop in itemKey) {
        queue[prop] = queue[prop] || [];
        queue[prop].push(itemKey[prop]);
      }
    });
    utils.compatibleMixin(pageMap, queue, this);
  },

  // 小程序页面事件将采用兼容式混合, 即先响应组件本身响应事件，然后再响应混合对象中响应事件。
  lifeCycleMixin(mixins, config) {
    let queue = {};
    let pageLifes = utils._getPageEvents(config);

    mixins.forEach((item) => {
      let itemKey = utils._getPageEvents(item.default || item);
      for (let prop in itemKey) {
        queue[prop] = queue[prop] || [];
        queue[prop].push(itemKey[prop]);
      }
    });
    utils.compatibleMixin(pageLifes, queue, this);
  },

  // 执行mixin中onLoad的方法。
  onLoadLifeMixin(context, mixins, config, args) {
    config.onLoad && config.onLoad.call(context, ...args);
    let onLoadQueue = [];
    // 执行mixin中onLoad的方法。
    mixins.forEach((item) => {
      onLoadQueue.push((item.default || item).onLoad);
    });
    utils._executeQueue(onLoadQueue, context, args);
  },
};


let mixins = function(config) {
  // data,events 采用 默认混合模式
  // 即如果组件未声明该数据，组件，事件，自定义方法等，那么将混合对象中的选项将注入组件这中。对于组件已声明的选项将不受影响
  // 对于组件methods响应事件，以及小程序页面事件将采用兼容式混合, 即先响应组件本身响应事件，然后再响应混合对象中响应事件。
  let mixins = config.mixins || [];

  // 每次只会执行一次
  function _$once() {
    let events = this.events ? this.events() : {};
    for (let key in events) {
      if (!Array.isArray(_events[key])) {
        _events[key] = [this, events[key]];
      }
    }
  }

  return {
    data: utils.defaultMixin(mixins, config, 'data'),
    // events: utils.defaultMixin(mixins, config, 'events'),

    onLoad() {
      // events, mixins
      utils.adapterMixin.call(this, mixins, config, 'events');
      utils.adapterMixin.call(this, mixins, config, 'methods');
      _$once.call(this);
      // 执行mixin中onLoad的方法。
      utils.onLoadLifeMixin(this, mixins, config, arguments);
      utils.lifeCycleMixin.call(this, mixins, config);
    },

    $emit(name) {
      let args = Array.prototype.slice.call(arguments, 1);
      let callbacks = _events[name];
      if (Array.isArray(callbacks)) {
        let self = callbacks[0];
        let callback = callbacks[1];
        callback.call(self, ...args);
      }
    },

    // invoke 方法(实现页面通讯)
    $invoke(router, method, ...args) {
      let pages = utils._getAllPages();
      let targetPage;
      for (let index = 0, _len = pages.length; index < _len; index++) {
        if (pages[index].__route__ === router) {
          targetPage = pages[index];
          break;
        }
      }
      try {
        targetPage[method].call(targetPage, ...args);
      } catch (error) {
        console.log(error, `not found ${router}页面的${method}方法`);
      }

    }
  };
};


module.exports = function createPage(config) {
  return Object.assign({}, config, mixins(config));
};