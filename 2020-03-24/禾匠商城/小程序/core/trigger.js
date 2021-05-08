module.exports = {
    taskList: [],
    events: {
        login: "LOGIN",
        callConfig: "CALLCONFIG"
    },
    add: function(t, s, i) {
        this.taskList && this.taskList.length || (this.taskList = []), this.taskList.push({
            event: t,
            name: s,
            fun: i
        });
    },
    remove: function(t, s) {
        for (var i in this.taskList) this.taskList[i] && this.taskList[i].event === t && this.taskList[i].name === s && (this.taskList[i] = null);
    },
    run: function(t, s, i) {
        for (var n in this.taskList) null != this.taskList[n] && this.taskList[n].event === t && ("function" == typeof this.taskList[n].fun && this.taskList[n].fun(i), 
        this.taskList[n] = null);
        "function" == typeof s && s();
    }
};