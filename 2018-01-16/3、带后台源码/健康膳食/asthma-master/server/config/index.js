export default {
    // db: "mongodb://localhost/xhnj",
    //db : "mongodb://192.168.0.163:27017/xhnj",
    db: "mongodb://xhnj:xhnj123@101.200.228.228:27017/xhnj",
    // options
    appId: "wx5cbeb3b042c9c4ba",
    appSecret: "cccbc4bfd727079c30bc9964e50e4a3f"
}

var options = {
  db: { native_parser: true },
  server: { poolSize: 5 },
  user: 'xhnj',
  pass: 'xhnj123'
}
