/**
 * Created by lingge on 2016/8/25.
 */
module.exports = {
    development: {
        dialect: "mysql",
        username: "root",
        password: "",
        port:"3307",
        database: "noteplay",
        charset:"utf-8",
        host: "localhost",
        timezone: '+08:00'
    },
    test: {
        username: "root",
        password: '',
        database: "noteplay",
        host: "localhost",
        dialect: "mysql",
        timezone: '+08:00'
    },
    production: {
        username: "root",
        password: '',
        database: "noteplay",
        host: "localhost",
        dialect: "mysql",
        timezone: '+08:00'
    }
};