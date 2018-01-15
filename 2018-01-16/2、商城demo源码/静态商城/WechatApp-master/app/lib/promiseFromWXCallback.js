import Promise from 'promiseEs6Fix';

export default fn => fnArgs => (
  new Promise((resolve, reject) => {
    fnArgs = fnArgs || {};

    fnArgs.success = (...args) => {
      resolve(...args);
    };

    fnArgs.fail = (...args) => {
      reject(...args);
    };

    fn(fnArgs);
  })
);
