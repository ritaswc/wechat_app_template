export default {
  data: {
    'a1': 'a1',
  },
  methods: {
    fn(data) {
      console.log('mixin1 fn', data)
    },
    onModal(e) {
      console.log(e);
    }
  },

  onLoad() {
    console.log('mixin1 onLoad');
  },

  onShow() {
    console.log('mixin1 onshow');
  },
  onReady() {
    console.log('mixin1 onReady')
  },
};