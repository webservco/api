# webservco/api

A PHP library for [webservco/framework](https://github.com/webservco/framework).

Base classes for building JSON:API endpoints and for reading responses from third-party APIs.

> **Note:** `webservco/framework` is considered legacy. It has been replaced by individual components, please see [webservco/sandbox](https://webservco.github.io/sandbox/).

- Implements: [JSON:API 1.0](https://jsonapi.org/format/1.0/) documents (content type `application/vnd.api+json`)
- Requires: PHP 8.3+, `webservco/framework`
- Related: `webservco/discogs-api` (its `ApiResponse` extends `AbstractResponse`)

---

## Installation

```shell
composer require webservco/api
```

---

## Usage

### Output a JSON:API document

```php
use WebServCo\Api\JsonApi\AbstractResourceObject;
use WebServCo\Api\JsonApi\Document;
use WebServCo\Api\JsonApi\Response;

final class ExampleResource extends AbstractResourceObject
{
}

// \WebServCo\Api\JsonApi\Interfaces\ResourceObjectInterface
$resource = new ExampleResource('examples');
$resource->setId('123');
$resource->setAttribute('title', 'Example');

$document = new Document();
$document->setData($resource);

// \WebServCo\Framework\Http\Response, with the JSON:API content type set
return new Response($document);
```

By default `Document` outputs a single resource object (`"data": {...}`, or `null` when it's empty). Pass `true` to the constructor to get a collection (`"data": [...]`) instead. To return errors, call `setStatus()` on each `Error` and add it with `setError()`. The document then outputs `errors` instead of `data`, with the status code of the last error.

### Validate an incoming request

Extend `AbstractSecurity` to check the request itself, and `AbstractClientRequest` to check its body.

```php
// Inside an AbstractSecurity subclass (implement verifyAuthorization()).
$this->setAllowedMethods([Method::GET, Method::POST]);
$this->setSupportedContentTypes([Document::CONTENT_TYPE]);
// Checks HTTPS, the method and the Accept header. Throws a framework HTTP exception if one fails.
$this->verify();
```

For `POST` requests, `AbstractClientRequest` decodes the JSON body. Its `verify()` then checks the `Content-Type`, `jsonapi.version`, `data.type` and `data.attributes`. Failures throw `Exceptions\ApiException` (HTTP 400).

---

## Contracts

| Class | Purpose |
| --- | --- |
| `JsonApi\Interfaces\ResourceObjectInterface` | A resource object: type, ID, attributes, links, meta. |
| `JsonApi\AbstractResourceObject` | Base resource object. Extend it for each resource type. |
| `JsonApi\Document` | Top-level document with `jsonapi`, `data` or `errors`, and `meta`. |
| `JsonApi\Error` | A JSON:API error object. |
| `JsonApi\Response` | HTTP response that outputs a `Document`. |
| `AbstractSecurity` | Checks HTTPS, the allowed methods and the `Accept` header. |
| `AbstractClientRequest` | Decodes and checks a JSON:API request body. |
| `AbstractResponse` | Wraps a third-party API response and decodes its body by content type. |

Namespaces are relative to `WebServCo\Api\`.

---

## Notes

`AbstractResponse` decodes JSON, form-encoded, plain text and HTML bodies. Any other content type throws `UnsupportedMediaTypeException`. Responses with status 204 or 205 are not decoded.

`AbstractSecurity::verify()` rejects every request that isn't HTTPS. That includes local development over plain HTTP.

---

## Development

```shell
# Start project.
ddev start
# Install dependencies.
ddev c u
# Run all validations.
ddev c c
# Run unit tests.
ddev c test:d
```

---

## License

[MIT](LICENSE)
