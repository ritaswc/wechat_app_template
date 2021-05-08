export default function deepCopy(source) {
  const result = {};
  for (const key in source) {
    if (source) {
      result[key] = typeof source[key] === 'object' ? deepCopy(source[key]) : source[key];
    }
  }
  const arr = [];
  result.forEach((o) => {
    arr.push(o);
  });
  return arr;
}
