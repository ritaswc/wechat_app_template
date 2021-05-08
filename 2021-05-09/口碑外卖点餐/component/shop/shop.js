let defaultData = {
    show : false
}

function toggleShopActivity(e){
    let dataset = e.currentTarget.dataset;
    this.setData({
        show : Object.assign({},this.data.show,{
            [dataset.shopid] : !this.data.show[dataset.shopid]
        })
    });
    console.log(this.data);
}
module.exports = {
    defaultData,
    toggleShopActivity
}