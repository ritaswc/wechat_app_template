const app = getApp();

Page({
  data: {
    items: {
      labelText: '已阅读并同意',
      iconType: 'circle',
      is_default: false
    }
  },
  setDefault() {
    const isDefault = this.data.items.is_default;
    const iconColor = this.data.items.is_default ? '#FF2D4B' : '';

    this.setData({
      items: {
        labelText: '已阅读并同意',
        iconType: isDefault ? 'success' : 'circle',
        is_default: !isDefault,
        iconColor
      }
    });
  },
  onLoad() {
  }
});
