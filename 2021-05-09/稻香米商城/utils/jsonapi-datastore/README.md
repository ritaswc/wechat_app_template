# jsonapi-datastore
[![Build Status](https://travis-ci.org/beauby/jsonapi-datastore.svg)](https://travis-ci.org/beauby/jsonapi-datastore)

JavaScript client-side [JSON API](http://jsonapi.org) data handling made easy.

Current version is v0.4.0-beta. It is still a work in progress, but should do what it says.

## Description

The [JSONAPI](http://jsonapi.org) standard is great for exchanging data (which is its purpose), but the format is not ideal to work directly with in an application.
jsonapi-datastore is a JavaScript framework-agnostic library (but an [AngularJS version](#angularjs) is provided for convenience) that takes away the burden of handling [JSONAPI](http://jsonapi.org) data on the client side.

What it does:
- read JSONAPI payloads,
- rebuild the underlying data graph,
- allows you to query models and access their relationships directly,
- create new models,
- serialize models for creation/update.

What it does not do:
- make requests to your API. You design your endpoints URLs, the way you handle authentication, caching, etc. is totally up to you.

## Installing

Install jsonapi-datastore with `bower` by running:
```
$ bower install jsonapi-datastore
```
or with `npm` by running:
```
$ npm install jsonapi-datastore
```

## Parsing data

Just call the `.sync()` method of your store.
```javascript
var store = new JsonApiDataStore();
store.sync(data);
```
This parses the data and incorporates it in the store, taking care of already existing records (by updating them) and relationships.

## Parsing with meta data

If you have meta data in your payload use the `.syncWithMeta` method of your store.
```javascript
var store = new JsonApiDataStore();
store.syncWithMeta(data);
```
This does everything that `.sync()` does, but returns an object with data and meta split.

## Retrieving models

Just call the `.find(type, id)` method of your store.
```javascript
var article = store.find('article', 123);
```
or call the `.findAll(type)` method of your store to get all the models of that type.
```javascript
var articles = store.findAll('article');
```
All the attributes *and* relationships are accessible through the model as object properties.
```javascript
console.log(article.author.name);
```
In case a related resource has not been fetched yet (either as a primary resource or as an included resource), the corresponding property on the model will contain only the `type` and `id` (and the `._placeHolder` property will be set to `true`). However, the models are *updated in place*, so you can fetch a related resource later, and your data will remain consistent.

## Serializing data

Just call the `.serialize()` method on the model.
```javascript
console.log(article.serialize());
```

## Examples

```javascript
// Create a store:
var store = new JsonApiDataStore();

// Then, given the following payload, containing two `articles`, with a related `user` who is the author of both:
var payload = {
  data: [{
    type: 'article',
    id: 1337,
    attributes: {
      title: 'Cool article'
    },
    relationships: {
      author: {
        data: {
          type: 'user',
          id: 1
        }
      }
    }
  }, {
    type: 'article',
    id: 300,
    attributes: {
      title: 'Even cooler article'
    },
    relationships: {
      author: {
        data: {
          type: 'user',
          id: 1
        }
      }
    }
  }]
};

// we can sync it:
var articles = store.sync(payload);

// which will return the list of synced articles.

// Later, we can retrieve one of those:
var article = store.find('article', 1337);

// If the author resource has not been synced yet, we can only access its id and its type:
console.log(article.author);
// { id: 1, _type: 'article' }

// If we do sync the author resource later:
var authorPayload = {
  data: {
    type: 'user',
    id: 1,
    attributes: {
      name: 'Lucas'
    }
  }
};

store.sync(authorPayload);

// we can then access the author's name through our old `article` reference:
console.log(article.author.name);
// 'Lucas'

// We can also serialize any whole model in a JSONAPI-compliant way:
console.log(article.serialize());
// ...
// or just a subset of its attributes/relationships:
console.log(article.serialize({ attributes: ['title'], relationships: []}));
// ...
```

## Documentation

See [DOCUMENTATION.md](DOCUMENTATION.md).

## What's missing

Currently, the store does not handle `links` attributes or resource-level or relationship-level meta.

## Notes

### AngularJS

jsonapi-datastore is bundled with an AngularJs wrapper. Just include `ng-jsonapi-datastore.js` in your `index.html` and require the module `beauby.jsonApiDataStore` in your application.
You can then use the `JsonApiDataStore` factory, which is essentially defined as follows:
```javascript
{
  store: new JsonApiDataStore(),
  Model: JsonApiDataStoreModel
}
```
so that you can use it as follows:

```javascript
angular
  .module('myApp')
  .controller('myController', function(JsonApiDataStore) {
    var article = JsonApiDataStore.store.find('article', 1337);
    var newArticle = new JsonApiDataStore.Model('article');
    newArticle.setAttribute('title', 'My cool article');
    console.log(newArticle.serialize());
  });
```


## Contributing

All pull-requests welcome!
