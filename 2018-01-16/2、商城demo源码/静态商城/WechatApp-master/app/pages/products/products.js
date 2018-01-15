import request from '../../lib/request';
import WxParse from '../../wxParse/wxParse';
import serviceData from '../../data/config';

Page({
  data: {
    product: [],
    current: 1,
    indicatorDots: false
  },
  currentchange(e) {
    this.setData({
      current: e.detail.current + 1
    });
  },
  onLoad(options) {
    const productId = options.id;
    var product = serviceData.productData;
    product.goods_price = product.goods_price.toFixed(2);
    this.setData({
      product,
      wxParseData: WxParse('html', product.goods_detail)
    ,cartNum:2});

  },
  onShareAppMessage(event){
    console.log(event);
    return {
          title: '商品页',
          desc: this.data.product.goods_name,
          path: '/products/products?id=' + this.data.product.id
        }
  },
  addCar() {

    const specData = this.data.product.spec;
    const stockData = this.data.product.stock; // 对应的库存量
    specData.forEach((specValue) => {
      (specValue.value).forEach((spec) => {
        spec.disabled = '';
        spec.class = 'class';
        let stockNum = 0;
        stockData.forEach((stock) => {
          if (typeof (stock.stock_spec_str) === 'string') {
            stock.stock_spec_str = stock.stock_spec_str.split(',');
          }
          (stock.stock_spec).forEach((stockSpex) => {
            if (Number(spec.value_id) === Number(stockSpex.value_id)) {
              // 累加对应口味的库存量
              stockNum += Number(stock.stock_num);
              spec.stock_num = stockNum;
            }
          });
        });
        if (Number(spec.stock_num) === 0) {
          // 库存量为0，则不可以点击
          spec.disabled = 'disabled';
          spec.class = 'disabled';
        }
      });
    })
     this.setData({
      product: this.data.product,
      popDisplay: 'block',
      subButton: {
        disabled: 'disabled',
        class: 'disabled'
      },
      addButton: {
        disabled: 'disabled',
        class: 'disabled'
      },
      buyNum: 1,
      buyNumClass: 'disabled',
      totalStock: this.data.product.total_stock,
      oldStockNum: this.data.product.total_stock,
      goodsPrice: this.data.product.goods_price,
      oldGoodsPrice: this.data.product.goods_price
    });

  },
  closePop() {
    this.setData({
      popDisplay: 'none'
    });
  },
  choseSpec(e) {
    const valueId = e.currentTarget.dataset.valueId; // 属性id
    const specId = e.currentTarget.dataset.specId; // 点击分类的id
    const product = this.data.product;
    const stockData = product.stock;
    const specData = product.spec;
    let totalNum = false;
    let goodsPrice = false;
    let skuId = 0;
    specData.forEach((spec) => {
      (spec.value).forEach((value) => {
        let choseId = '';
        if (Number(value.value_id) === Number(valueId)) {
          // 点击遍历分类属性，选中效果切换
          if (value.class === 'selected') {
            value.class = '';
            this.data.buyNumClass = 'disabled';
            this.data.buyNum = 1;
            // 点击修改已选中的规格参数，删除对应的value_id
            this.data.choseArr.forEach((citem, index) => {
              if (specId === citem.spec_id) {
                citem.value_id = '';
              }
              if (index === 0) {
                choseId += citem.value_id;
              } else {
                choseId += `,${citem.value_id}`;
              }
            });
            specData.forEach((specValue) => {
              (specValue.value).forEach((specdata) => {
                if (Number(specdata.stock_num) === 0) {
                  // 库存量为0，则不可以点击
                  specdata.disabled = 'disabled';
                } else if (Number(specValue.spec_id) === Number(specId)) {
                  specdata.disabled = '';
                  specdata.class = '';
                }
              });
            });
            totalNum = this.data.oldStockNum;
            goodsPrice = this.data.oldGoodsPrice;
            this.data.addButton.class = 'disabled';
            this.data.addButton.disabled = 'disabled';
            this.data.subButton.class = 'disabled';
            this.data.subButton.disabled = 'disabled';
            skuId = 0;
          } else {
            value.class = 'selected';
            let weightId = '';
            const arritem = {};
            let isCon = false;
            // 点击修改已选中的规格参数，修改对应的value_id
            this.data.choseArr.forEach((citem) => {
              if (specId === citem.spec_id) {
                isCon = true;
                citem.value_id = valueId;
              }
            });
            if (!isCon) {
              arritem.spec_id = specId;
              arritem.value_id = valueId;
              this.data.choseArr.push(arritem);
            }
            const arrArr = [];
            this.data.choseArr.forEach((citem) => {
              arrArr.push(citem.value_id);
            });
            arrArr.sort((a, b) => b - a);
            arrArr.forEach((citem, index) => {
              if (index === 0) {
                choseId += citem;
              } else {
                choseId += `,${citem}`;
              }
            });
            // 选中去修改该属性下对应的规格是否还有库存
            // 规格选完全后实时修改库存量
            stockData.forEach((stock) => {
              if (typeof (stock.stock_spec_str) === 'object') {
                let stockSpecStr = '';
                stock.stock_spec_str.sort((a, b) => b - a);
                stock.stock_spec_str.forEach((sitem, index) => {
                  if (index === 0) {
                    stockSpecStr += sitem;
                  } else {
                    stockSpecStr += `,${sitem}`;
                  }
                });
                stock.stock_spec_str = stockSpecStr;
              }
              if (stock.stock_spec_str === choseId) {
                totalNum = stock.stock_num;
                goodsPrice = stock.stock_price;
                skuId = stock.stock_id;
                this.data.buyNumClass = '';
                this.data.buyNum = 1;
                if (totalNum > 1) {
                  this.data.addButton.disabled = '';
                  this.data.addButton.class = '';
                } else {
                  this.data.addButton.disabled = 'disabled';
                  this.data.addButton.class = 'disabled';
                  this.data.subButton.disabled = 'disabled';
                  this.data.subButton.class = 'disabled';
                }
              }
              if (stock.stock_spec_str.indexOf(value.value_id) !== -1 &&
                Number(stock.stock_num) === 0) {
                const idArray = stock.stock_spec_str.split(',');
                idArray.forEach((dataId) => {
                  if (Number(dataId) !== Number(valueId)) {
                    weightId = dataId;
                  }
                });
              }
              // 拿到库存为0的value_id，设置为不可以点击
              specData.forEach((specValue) => {
                (specValue.value).forEach((valueItem) => {
                  if (weightId !== '' && Number(valueItem.value_id) === Number(weightId)) {
                    valueItem.disabled = 'disabled';
                    valueItem.class = 'disabled';
                  }
                });
              });
            });
          }
        } else if (Number(spec.spec_id) === Number(specId) && value.stock_num !== 0) {
          // 属于同个属性并且库存不为0的去除选中样式
          value.class = '';
          value.disabled = '';
        } else if (value.stock_num === 0) {
          value.class = 'disabled';
          value.disabled = 'disabled';
        }
      });
    });
    let price = (goodsPrice && goodsPrice.toFixed(2)) || this.data.oldGoodsPrice;
    this.setData({
      product,
      totalStock: totalNum || this.data.oldStockNum,
      goodsPrice: price,
      addButton: this.data.addButton,
      subButton: this.data.subButton,
      buyNum: this.data.buyNum,
      buyNumClass: this.data.buyNumClass,
      skuId
    });
  },
  addShopNum() {
    let buyNum = this.data.buyNum;
    const totalNum = this.data.totalStock;
    buyNum += 1;
    if (buyNum < totalNum && buyNum !== 1) {
      this.data.subButton.class = '';
      this.data.subButton.disabled = '';
    } else {
      this.data.addButton.class = 'disabled';
      this.data.addButton.disabled = 'disabled';
    }

    this.setData({
      buyNum,
      addButton: this.data.addButton,
      subButton: this.data.subButton
    });
  },
  subShopNum() {
    let buyNum = this.data.buyNum;
    buyNum -= 1;
    if (buyNum === 1) {
      this.data.addButton.class = '';
      this.data.addButton.disabled = '';
      this.data.subButton.class = 'disabled';
      this.data.subButton.disabled = 'disabled';
    }

    this.setData({
      buyNum,
      addButton: this.data.addButton,
      subButton: this.data.subButton
    });
  },
  submitCart() {
    const data = {
      goods_id: this.data.product.id,
      sku_id: this.data.skuId,
      goods_number: this.data.buyNum
    };

    if (data.sku_id) {
      request({ path: '/cart/addCart', data, method: 'post' }).then((res) => {
        if (res) {
          this.setData({
            popDisplay: 'none',
            cartNum: this.data.cartNum + this.data.buyNum
          });
        }
      });
    } else {
      this.setData({
        toast: {
          toastClass: 'yatoast',
          toastMessage: '请先选择属性'
        }
      });

      setTimeout(() => {
        this.setData({
          toast: {
            toastClass: '',
            toastMessage: ''
          }
        });
      }, 2000);
    }
  },
  navigateToCart() {
    wx.switchTab({
      url: '../cart/cart'
    });
  }
});
