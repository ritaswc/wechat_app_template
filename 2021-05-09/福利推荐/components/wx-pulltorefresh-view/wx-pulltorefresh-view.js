Component({
  properties: {
    pullText: {
      type: String,
      value: '上拉加载更多',
    },
    releaseText: {
      type: String,
      value: '松开立即刷新',
    },
    loadingText: {
      type: String,
      value: '正在刷新数据中',
    },
    finishText: {
      type: String,
      value: '刷新完成',
    },
    pullUpText: {
      type: String,
      value: '上拉加载更多',
    },
    pullUpReleaseText: {
      type: String,
      value: '松开立即加载',
    },
    loadmoreText: {
      type: String,
      value: '正在加载更多数据',
    },
    loadmoreFinishText: {
      type: String,
      value: '加载完成',
    },
    nomoreText: {
      type: String,
      value: '已经全部加载完毕',
    },
    refreshing: {
      type: Boolean,
      value: false,
      observer: 'refreshingChange',
    },
    nomore: {
      type: Boolean,
      value: false,
    },
    disablePullDown: {
      type: Boolean,
      value: false,
    },
    disablePullUp: {
      type: Boolean,
      value: false,
    },
  },
  data: {
    pullDownStatus: 0,
    pullUpStatus: 0,
    offsetY: 40,
    isOutBound: false,
    eventName: '',
  },
  methods: {
    scrollToBottom: function() {
      const query = this.createSelectorQuery()
      query.select('#pulltorefresh-view-container').boundingClientRect()
      query.exec((function(res){
        const innerHeight = res[0].height
        query.select('#pulltorefresh-view').boundingClientRect()
        query.exec((function(res) {
          const outerHeight = res[0].height
          this.setData({
            pullUpStatus: 3,
            offsetY: -(innerHeight - outerHeight - 40),
          })
          setTimeout((function() {
            this.setData({
              pullUpStatus: 0,
            })
          }).bind(this), 500)
        }).bind(this))
      }).bind(this))
    },
    touchend: function(e) {
      if (this.data.isOutBound && this.data.offsetY > 40 && !this.properties.disablePullDown) {
        this.setData({
          pullDownStatus: 2,
          eventName: 'pulldownrefresh',
        })
        this.triggerEvent('pulldownrefresh');
      } else if (this.data.isOutBound && this.data.offsetY < 40 && !this.properties.disablePullUp) {
        if (this.properties.nomore) {
          this.scrollToBottom()
        } else {
          this.setData({
            pullUpStatus: 2,
            eventName: 'loadmore',
          })
          this.triggerEvent('loadmore');
        }
      } else {
        this.setData({
          offsetY: 40,
          pullDownStatus: 0,
        })
      }
    },
    change: function(e) {
      if (this.properties.refreshing) return
      this.data.isOutBound = e.detail.source == 'touch-out-of-bounds'
      this.data.offsetY = e.detail.y + 40
      if (this.data.isOutBound) {
        if (this.data.offsetY > 40) {
          this.setData({
            pullDownStatus: 1,
          })
        } else {
          this.setData({
            pullUpStatus: 1,
          })
        }
      } else if ((this.data.pullDownStatus != 0 && this.data.pullDownStatus != 3) || (this.data.pullUpStatus != 0 && this.data.pullUpStatus != 3)) {
        this.setData({
          pullDownStatus: 0,
          pullUpStatus: 0,
        })
      }
      if (this.data.isOutBound && this.data.offsetY > 40 && this.data.pullDownStatus == 0) {
        this.setData({
          pullDownStatus: 1,
        })
      } else if (this.data.offsetY <= 40 && this.data.pullDownStatus == 1) {
        this.setData({
          pullDownStatus: 0,
        })
      }
    },
    refreshingChange: function(newVal, oldVal) {
      if (oldVal === true && newVal === false) {
        if (this.data.eventName == 'pulldownrefresh') {
          this.setData({
            offsetY: 40,
            pullDownStatus: 3,
          })
          setTimeout(() => {
            this.setData({
              pullDownStatus: 0,
            })
          }, 500);
        } else if (this.data.eventName = 'loadmore') {
          this.scrollToBottom()
        }
      }
    },
  },
})