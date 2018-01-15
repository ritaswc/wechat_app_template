import resource from '../../lib/resource';
import tips from '../../lib/tips';

Page({
  data: {
    // 设置菊花初始状态
    loading: true,
    addressesList: [],
    defaultId: 0,
    tipsData: {
      title: ''
    }
  },
  setDefaultStyle(list, id) {
    list.forEach((itm) => {
      if (itm) {
        itm.items.is_default = +itm.address_id === id;
        itm.items.iconType = itm.items.is_default ? 'success' : 'circle';
        itm.items.iconColor = itm.items.iconType === 'success' ? '#FF2D4B' : '';
      }
    });
  },
  goEdit(event) {
    const id = event.target.dataset.addressId;
    wx.navigateTo({
      url: `../address-edit/address-edit?id=${id}`
    });
  },
  delete(event) {
    const id = event.target.dataset.addressId;
    let addressList = this.data.addressesList;

    resource.confirmToast(() => {
      resource.deleteAddress(id).then((res) => {
        if (res.statusCode === 200) {
          resource.successToast(() => {
            const defaultData = addressList.find(itm => itm.items.is_default === true);
            if (+defaultData.address_id === +id && addressList.length > 0) {
              addressList = addressList.filter(itm => +itm.address_id !== +id);
              // addressList.forEach((itm) => {

              // });
              // addressList[0].items.is_default = true;
              // addressList[0].items.iconType = 'success';
              // addressList[0].items.iconColor = '#FF2D4B';
            }
            this.setData({
              defaultId: defaultData.address_id,
              addressesList: addressList.filter(itm => +itm.address_id !== +id)
            });
          });
        }
      });
    });
  },
  setDefault(event) {
    const checkedId = +event.currentTarget.dataset.valueId || +event.detail.value;
    let setFlag = false;
    resource.loadingToast();
    resource.setDefaultAddress(checkedId).then((res) => {
      if (res.statusCode === 200) {
        setFlag = true;
        this.setDefaultStyle(this.data.addressesList, checkedId);
        this.setData({ addressesList: this.data.addressesList });
      } else {
        setFlag = false;
      }
      return setFlag;
    }).then((flag) => {
      if (flag) {
        wx.showToast({
          title: '默认地址设置成功',
          icon: 'success'
        });
      } else {
        // wx.failToast();
      }
    });
  },
  onLoad() {
    tips.toast(this.data.tipsData);
    const tipsData = {
      title: 'sku不足zz',
      duration: 2000,
      isHidden: false
    };
    this.setData({
      tipsData
    });
    setTimeout(() => {
      tipsData.isHidden = true;
      this.setData({
        tipsData
      });
    }, 3000);
    resource.fetchAddresses().then((res) => {
      console.log(res.data);
      if (res.data) {
        res.data.forEach((itm) => {
          itm.overlayConfirm = false;
          itm.items = {
            id: itm.address_id,
            is_default: itm.is_default,
            isgroup: true,
            labelText: '设置为默认',
            iconType: itm.is_default ? 'success' : 'circle'
          };
          itm.items.iconColor = itm.items.iconType === 'success' ? '#FF2D4B' : '';
        });
        console.log(res);
        this.setData({
          addressesList: res.data,
          loading: false
        });
      } else {
        this.setData({
          addressesList: [],
          loading: false
        });
      }
    });
  }
});
