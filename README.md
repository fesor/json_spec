Json Spec
===================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fesor/json_spec/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fesor/json_spec/?branch=master) [![Build Status](https://travis-ci.org/fesor/json_spec.svg?branch=master)](https://travis-ci.org/fesor/json_spec)

Json Spec provides set of easy-to-use matcher that should help you to validate data in JSON responses from your api with less pain.

If you working with JSON-based REST APIs there are several issues:

- You can't simple check is a response is equal to given string as there is things like server-generated IDs or keys sorting.
- Key ordering should be the same both for your API and for expected JSON.
- Matching the whole responses breaks DRY for the spec

`json_spec` solves this problems as it normalize JSON before match it.

Let's see simple example:

<table>
  <tr>
    <td>
      <pre>
<code>
{
   "id": 1421,
   "created_at": "1977-05-25 00:00:00"
   "last_name": "Skywalker",
   "first_name": "Luke",
}
        </code>
      </pre>
    </td>
    <td>
      <pre>
<code>
{
   "first_name": "Luke",
   "last_name": "Skywalker"
}
        </code>
      </pre>
    </td>
  </tr>
</table>

`json_spec` will assume that this JSON documents are equal. Before asserting, `json_spec` will exclude keys `id`, `created_at` and `updated_at` from response JSON (List of excluded keys is configurable). Then it will normalize JSON (reorder keys, pretty-print) and after, just check for string equality. That's all. Also you can match JSON by given path instead of describing whole response in your specification, check is JSON collection contains some record and many more.

## Installation

To install `json_spec` you may want to use composer:
```
composer require --dev fesor/json_spec
```

Then follow instructions for your testing framework.

 - [Using json_spec with PhpSpec](docs/phpspec.md)
 - [Using json_spec with Behat](docs/behat.md)

### More

Check out the [specs](https://github.com/fesor/json_spec/blob/master/spec)
and [features](https://github.com/fesor/json_spec/blob/master/features) to see all the
various ways you can use json_spec.

## Contributing
If you come across any issues, please [tell me](https://github.com/fesor/json_spec/issues) . Pull requests (with tests) are appreciated. No pull request is too small. Please help with:

- Reporting bugs
- Suggesting features
- Writing or improving documentation
- Fixing typos
- Cleaning whitespace
- Refactoring code
- Adding tests
- Closing [issues](https://github.com/fesor/json_spec/issues)

If you report a bug and don't include a fix, please include a failing test.

## Credits
- [json_spec](https://github.com/collectiveidea/json_spec) - Ruby's gem for handling JSON in RSpec and Cucumber. This library is mainly just php port of this great library.

