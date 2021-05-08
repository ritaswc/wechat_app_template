var pageData = {}
pageData.data = {
        img: ['1', '0', '02', '01', '2', '3'],
        indicatorDots: true,
        vertical: false,
        autoplay: true,
        interval: 3000,
        duration: 1000,
        circular: true
} 
var toastNum = 1
for(var i = 0; i <= toastNum; ++i) {
  pageData.data['toast'+i+'Hidden'] = true;
  (function (index) {
    pageData['toast'+index+'Change'] = function(e) {
      var obj = {}
      obj['toast'+index+'Hidden'] = true;
      this.setData(obj)
    }
    pageData['toast'+index+'Tap'] = function(e) {
      var obj = {}
      obj['toast'+index+'Hidden'] = false
      this.setData(obj)
    }
  })(i)
}
Page(pageData)