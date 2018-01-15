export default {
  data: {
    'a2': 'a2',
  },
  methods: {
    fn() {
      console.log('mixin2 fn')
    },
    fn2() {
      console.log('mixin2 fn2')
    },
    fn5() {
      console.log('mixin2 fn5')
    }
  },
  onLoad() {
    console.log('mixin2 onLoad');
  },
  onShow() {
    console.log('mixin2 onshow');
  },
};