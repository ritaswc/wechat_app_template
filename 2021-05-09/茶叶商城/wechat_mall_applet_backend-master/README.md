# README

This Rails project is a backend reference source for [wechat mall applet](https://github.com/bayetech/wechat_mall_applet), it need baye internal db to running, so normally, you can not running in your dev env, but only can reference.

# To generate swagger docs

```bash
bundle exec rake swagger:docs RAILS_ENV=production
```

# Known issue

Need change [basePath](https://github.com/richhollis/swagger-docs/pull/144) of `swagger_doc/api-docs.json` from empty to `http://localhost:3000/swagger_doc` in file `public/swagger_doc/api-docs.json`, then using `http://localhost:3000/swagger_doc/api-docs.json` to access.

staging env change to 

```json
  "basePath": "https://rapi-staging.bayekeji.com/swagger_doc",
```

Using `https://rapi-staging.bayekeji.com/swagger_doc/api-docs.json` to access.

[RAPI swagger UI site](https://swagger-ui.bayekeji.com/?url=https://rapi-staging.bayekeji.com/swagger_doc/api-docs.json#!/products/Products_index)