/*冒泡排序*/
function test(arr) {
    for (var i=0;i<arr.length;i++){
        for (var j=0;j<arr.length-1;j++){
            toCon(j,j+1)
        }
    }

    function toCon(pre,next) {
        var tmp='';
        if (arr[pre]>arr[next]){

            tmp=arr[pre];
            arr[pre]=arr[next];
            arr[next]=tmp;
        }
    }
    return arr;
}
console.log(test([2,8,1,3,6,9,7,5]))