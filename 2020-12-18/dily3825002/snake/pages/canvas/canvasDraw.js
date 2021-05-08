// 判断 是否绘图
function isDrawing( x, y, curScreenRange ) {
    if( x < curScreenRange.xRange.min ) {
        return false
    }

    if( x > curScreenRange.xRange.max ) {
        return false
    }

    if( y < curScreenRange.yRange.min ) {
        return false
    }

    if( y > curScreenRange.yRange.max ) {
        return false
    }

    return true
}


// 绘图函数
function drawing( x, y, col, radius, context, curScreenRange ) {
    if( isDrawing( x, y, curScreenRange ) ) {
        context.beginPath( 0 )
        context.arc( x, y, radius, 0, Math.PI * 2 )
        context.setFillStyle( col )
        context.setStrokeStyle( 'rgba(1,1,1,0)' )
        context.fill()
        context.stroke()
    }


}

module.exports = {
    drawing: drawing
}
