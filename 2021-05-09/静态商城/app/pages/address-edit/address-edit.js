import resource from '../../lib/resource';
import city from '../../lib/city';
import tips from '../../lib/tips';

Page({
  data: {
    loading: true,
    consignee: '',
    mobile: '',
    country: '',
    address: '',
    addressid: '',
    items: {
      labelText: '设置为默认',
      iconType: 'circle',
      is_default: false
    },
    index: 0,
    tipsData: {
      title: '',
      isHidden: true
    }
  },
  setDefault() {
    const isDefault = this.data.items.is_default;
    const iconColor = !this.data.items.is_default ? '#FF2D4B' : '';

    this.setData({
      items: {
        labelText: '设置为默认',
        iconType: !isDefault ? 'success' : 'circle',
        is_default: !isDefault,
        iconColor
      }
    });
  },
  onLoad(options) {
    this.setData({ addressid: options.id });
    var that = this;
 
    if (options.id) {
      resource.fetchDetailAddress(options.id).then((res) => {
        this.data.items.is_default = res.data.is_default;
        this.setData({
          consignee: res.data.consignee,
          mobile: res.data.mobile,
          county: res.data.county,
          province: res.data.province,
          city: res.data.city,
          address: res.data.address,
          loading: false,
          items: this.data.items
        });
        //this.setDefault();
          city.init(that);
      });
    } else {
        city.init(that);
    }
     
  },
  listenerReciverInput(e) {
    this.data.consignee = e.detail.value;
  },
  listenerPhoneInput(e) {
    this.data.mobile = e.detail.value;
  },
  listenerAddressInput(e) {
    this.data.address = e.detail.value;
  },
  showToast(title, duartion) {
    const that = this;
    const tipsData = {
      title: title || '',
      duartion: duartion || 2000,
      isHidden: false
    };
    tips.toast(that.data.tipsData);
    that.setData({
      tipsData
    });
    setTimeout(() => {
      tipsData.isHidden = true;
      that.setData({
        tipsData
      });
    }, tipsData.duartion);
  },
  submitBtn() {
    const that = this;
    if (!this.data.consignee) { that.showToast('收货人不能为空'); return; }
    if (this.data.consignee.length < 2) { that.showToast('收货人姓名限制为2~15个字符'); return; }
    if (!this.data.mobile) { that.showToast('手机号不能为空'); return; }
    if (!/^1[3|4|5|7|8]\d{9}$/.test(this.data.mobile)) { that.showToast('手机格式有误，请重新输入'); return; }
     if (this.data.city.provinceIndex == 0) { that.showToast('省市地址不能为空'); return; }
    if (!this.data.address) { that.showToast('街道地址不能为空'); return; }
    if (this.data.address.length < 5) { that.showToast('街道地址字数必须在5~60之间'); return; }

    console.log(this.data.city);
    const data = {
      consignee: this.data.consignee,
      province: this.data.addressSelect.provinceIdx[this.data.addressSelect.provinceIndex],
      city: this.data.addressSelect.cityIdx[this.data.addressSelect.provinceIndex][this.data.addressSelect.cityIndex],
      county: this.data.addressSelect.districtIdx[this.data.addressSelect.cityIdx[this.data.addressSelect.provinceIndex][this.data.addressSelect.cityIndex]][this.data.addressSelect.districtIndex],
      address: this.data.address,
      mobile: this.data.mobile,
      is_default: this.data.items.is_default ? 1 : 0
    };
    resource.postDetailAddress(this.data.addressid, data).then((res) => {
      if (res.statusCode === 200 || res.statusCode === 201) {
        resource.successToast(() => {
          wx.navigateTo({
            url: '../addresses/addresses'
          });
        });
      } else { console.log(res); }
    });
  },
  bindPickerChange(e) {
    this.setData({
      index: e.detail.value
    });
  }
});
