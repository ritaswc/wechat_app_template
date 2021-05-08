module.exports = {
    taskList: [],
    events: {
        login: 'LOGIN',
        callConfig: 'CALLCONFIG',
    },
    add: function (evevt, name, fun) {
        if (!this.taskList || !this.taskList.length) {
            this.taskList = [];
        }
        this.taskList.push({
            event: evevt,
            name: name,
            fun: fun,
        })
    },
    remove: function (event, name) {
        for (let i in this.taskList) {
            if (this.taskList[i] && this.taskList[i].event === event && this.taskList[i].name === name) {
                this.taskList[i] = null;
            }
        }
    },
    run: function (event, callback, param) {
        for (let i in this.taskList) {
            if (this.taskList[i] !=null && this.taskList[i].event === event) {
                if (typeof this.taskList[i].fun === 'function') {
                    this.taskList[i].fun(param);
                }
                this.taskList[i] = null;
            }
        }
        if (typeof callback === 'function')
            callback();
    }
};
