function colorValues() {
    var colorObj = new Object();
    colorObj.colorList = [ '#FFFFFF', '#DDDDDD', '#AAAAAA', '#888888', '#666666', '#444444', '#000000', '#FFB7DD', '#FF88C2', '#FF44AA', '#FF0088', '#C10066', '#A20055', '#8C0044', '#FFCCCC', '#FF8888', '#FF3333', '#FF0000', '#CC0000', '#AA0000', '#880000', '#FFC8B4', '#FFA488', '#FF7744', '#FF5511', '#E63F00', '#C63300', '#A42D00', '#FFDDAA', '#FFBB66', '#FFAA33', '#FF8800', '#EE7700', '#CC6600', '#BB5500', '#FFEE99', '#FFDD55', '#FFCC22', '#FFBB00', '#DDAA00', '#AA7700', '#886600', '#FFFFBB', '#FFFF77', '#FFFF33', '#FFFF00', '#EEEE00', '#BBBB00', '#888800', '#EEFFBB', '#DDFF77', '#CCFF33', '#BBFF00', '#99DD00', '#88AA00', '#668800', '#CCFF99', '#BBFF66', '#99FF33', '#77FF00', '#66DD00', '#55AA00', '#227700', '#99FF99', '#66FF66', '#33FF33', '#00FF00', '#00DD00', '#00AA00', '#008800', '#BBFFEE', '#77FFCC', '#33FFAA', '#00FF99', '#00DD77', '#00AA55', '#008844', '#AAFFEE', '#77FFEE', '#33FFDD', '#00FFCC', '#00DDAA', '#00AA88', '#008866', '#99FFFF', '#66FFFF', '#33FFFF', '#00FFFF', '#00DDDD', '#00AAAA', '#008888', '#CCEEFF', '#77DDFF', '#33CCFF', '#00BBFF', '#009FCC', '#0088A8', '#007799', '#CCDDFF', '#99BBFF', '#5599FF', '#0066FF', '#0044BB', '#003C9D', '#003377', '#CCCCFF', '#9999FF', '#5555FF', '#0000FF', '#0000CC', '#0000AA', '#000088', '#CCBBFF', '#9F88FF', '#7744FF', '#5500FF', '#4400CC', '#2200AA', '#220088', '#D1BBFF', '#B088FF', '#9955FF', '#7700FF', '#5500DD', '#4400B3', '#3A0088', '#E8CCFF', '#D28EFF', '#B94FFF', '#9900FF', '#7700BB', '#66009D', '#550088', '#F0BBFF', '#E38EFF', '#E93EFF', '#CC00FF', '#A500CC', '#7A0099', '#660077', '#FFB3FF', '#FF77FF', '#FF3EFF', '#FF00FF', '#CC00CC', '#990099', '#770077' ]
    colorObj.colorListLength = colorObj.colorList.length
    // 从 颜色列表 获取一个值
    colorObj.getColorValue = function getColorValue( index ) {

        if( index >= colorObj.colorListLength ) {
            index = index % colorObj.colorListLength
        }
        return colorObj.colorList[ index ]
    }
    return colorObj
}


module.exports = {
  colorValues: colorValues
}