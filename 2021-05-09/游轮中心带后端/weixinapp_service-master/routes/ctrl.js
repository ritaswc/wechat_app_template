

module.exports = function (app, routes) {
    app.post('/service/:sql',routes.servicedo);
    app.get('/demo',routes.demo);
};