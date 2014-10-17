Json Spec
===================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fesor/json_spec/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fesor/json_spec/?branch=master) [![Build Status](https://travis-ci.org/fesor/json_spec.svg?branch=master)](https://travis-ci.org/fesor/json_spec)

If you working with JSON-based REST APIs there are several issues:

- You can't simple check is a response is equal to given string as there is things like server-generated IDs or keys sorting
- Matching the whole responses breaks DRY for the spec

`json_spec` solves this problems be providing JSON normalization, key exclusion and json paths.

From `json_spec` point of view, JSON documents:
```
{
   "first_name": "Luke",
   "last_name": "Skywalker"
}
```
and
```
{
   "id": 1421,
   "created_at": "1977-05-25 00:00:00"
   "last_name": "Skywalker",
   "first_name": "Luke",
}
```
are equal.

Before asserting, `json_spec` will exclude keys `id`, `created_at` and `updated_at` from response from response JSON (List of excluded keys is configurable). Then it will normalize JSON (reorder keys, pretty-print) and after, just check for string equality. That's all. Also you can check JSON by given path instead of describing whole response in your specification.

## PhpSpec

JsonSpec extension provides five new PhpSpec matchers:

- `beJsonEqual`, `beJsonEqualFile`
- `includeJson`, `includeJsonFile`
- `haveJsonPath`
- `haveJsonType`
- `haveJsonSize`

You may use it in your specs as follow:
```php
class UserSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith('Steve', 'Richert');
    }

    function it_includes_names()
    {
        $names = '{"first_name":"Steve","last_name":"Richert"}';
        $this->toJson()->shouldBeJsonEqual($names)->excluding('friends');
    }

    function it_includes_the_ID()
    {
        $this->toJson()->shouldHaveJsonPath('id');
        $this->toJson()->shouldHaveJsonType('integer')->atPath('id');
    }

    function it_includes_friends()
    {
        $this->toJson()->shouldHaveJsonSize(0)->atPath('friends');

        $friend = new User("Catie" , "Richert");
        $this->addFriend($friend);

        $this->toJson()->shouldHaveJsonSize(1)->atPath('friends');
        $this->toJson()->shouldIncludeJson($friend->toJson());
    }

}

```

To install this extension, simply add following into your `phpspec.yml`:
```
extensions:
   - JsonSpec\PhpSpec\Extension
```

That's it.

## Exclusions
Json Spec ingores `id` hash key by default when comparing JSON. It's oftentimes helpful when evaluating JSON representations of newly-created records so that certain values don't have to be known. For example, if you store data in MongoDB, then ID will be always uniqe string. If you want to chane set of excluded keys, you may do this in `phpspec.yml`:
```
json_spec:
    excluded_keys: ['created_at', 'updated_at']
```
With this configuration, the `id` key will be included in JSON comparison, while `created_at` and `updated_at` won't. Keys can also be excluded/included per matcher by chaining the `excluding` or `including` methods (as shown above) which will add or subtract from the globally excluded keys, respectively.

## Paths
Each of JsonSpec matchers deal with JSON "paths." These are simple strings of "/" separated hash keys and array indexes. For instance, with the following JSON:
```javascript
{
  "first_name": "Steve",
  "last_name": "Richert",
  "friends": [
    {
      "first_name": "Catie",
      "last_name": "Richert"
    }
  ]
}

```
We could access the first friend's first name with the path `"friends/0/first_name"`.

## Behat
json_spec provides Behat context which implements steps utilizing json_spec matchers. This is perfect for testing your app's JSON API.

In order to use json_spec with behat you should enable json_spec behat extension and add context provided with json_spec in your ```behat.yml```. For example:
```
default:
    suites:
        default:
            contexts:
                - FeatureContext
                - json_spec
    extensions:
        JsonSpec\Behat\Extension: ~
```

One note. `json_spec` should have access to responses. If you are using Mink, that it's just fine. `json_spec` will get responses from Mink. This means that all you need to do to start working, is just to enable MinkExtension in your `behat.yml`:
```
default:
    suites:
        default:
            contexts:
                - FeatureContext
                - json_spec
    extensions:
        JsonSpec\Behat\Extension: ~
        Behat\MinkExtension:
            base_url:  'http://localhost:8047'
            sessions:
                default:
                    goutte: ~
```

That's all, now `json_spec` have access to all responses. You may also want to use `behatch:rest` context from [sanpii/behatch-contexts](https://github.com/sanpii/behatch-contexts) instead of mink context.

If you are using your own context, which not using Mink, then just implement ```JsonHolderAware``` interface for your context:

```php
use \JsonSpec\Behat\Context\JsonHolderAware;
use \Behat\Behat\Context\Context;

class MyRestApiFeatureContext implements Context, JsonHolderAware
{
    /**
     * @var \JsonSpec\Behat\JsonProvider\JsonHolder
     */
    private $jsonHolder;

    /**
     * @When /^I request "([^"]*)"$/
     */
    public function iRequest($pageUrl)
    {
        // ... make request and get response body as string
        $this->jsonHolder->setJson($responseBody);
    }
}
```

Now, you can use the json_spec steps in your features:

```
Feature: User API
  Background:
    Given the following users exist:
      | id | first_name | last_name |
      | 1  | Steve      | Richert   |
      | 2  | Catie      | Richert   |
    And "Steve Richert" is friends with "Catie Richert"

  Scenario: Index action
    When I visit "/users.json"
    Then the JSON response should have 2 users
    And the JSON response at "0/id" should be 1
    And the JSON response at "1/id" should be 2

  Scenario: Show action
    When I visit "/users/1.json"
    Then the JSON response at "first_name" should be "Steve"
    And the JSON response at "last_name" should be "Richert"
    And the JSON response should have "created_at"
    And the JSON response at "created_at" should be a string
    And the JSON response at "friends" should be:
      """
      [
        {
          "id": 2,
          "first_name": "Catie",
          "last_name": "Richert"
        }
      ]
      """
```

The background steps above and the "visit" steps aren't  provided by json_spec. The remaining steps, json_spec provides. They're versatile and can be used in plenty of different formats:

```
Then the JSON should be:
  """
  {
    "key": "value"
  }
  """
Then the JSON at "path" should be:
  """
  [
    "entry",
    "entry"
  ]
  """

Then the JSON should be {"key":"value"}
Then the JSON at "path" should be {"key":"value"}
Then the JSON should be ["entry","entry"]
Then the JSON at "path" should be ["entry","entry"]
Then the JSON at "path" should be "string"
Then the JSON at "path" should be 10
Then the JSON at "path" should be 10.0
Then the JSON at "path" should be 1e+1
Then the JSON at "path" should be true
Then the JSON at "path" should be false
Then the JSON at "path" should be null

Then the JSON should include:
  """
  {
    "key": "value"
  }
  """
Then the JSON at "path" should include:
  """
  [
    "entry",
    "entry"
  ]
  """

Then the JSON should include {"key":"value"}
Then the JSON at "path" should include {"key":"value"}
Then the JSON should include ["entry","entry"]
Then the JSON at "path" should include ["entry","entry"]
Then the JSON should include "string"
Then the JSON at "path" should include "string"
Then the JSON should include 10
Then the JSON at "path" should include 10
Then the JSON should include 10.0
Then the JSON at "path" should include 10.0
Then the JSON should include 1e+1
Then the JSON at "path" should include 1e+1
Then the JSON should include true
Then the JSON at "path" should include true
Then the JSON should include false
Then the JSON at "path" should include false
Then the JSON should include null
Then the JSON at "path" should include null

Then the JSON should have "path"

Then the JSON should be a hash
Then the JSON at "path" should be an array
Then the JSON at "path" should be a float

Then the JSON should have 1 entry
Then the JSON at "path" should have 2 entries
Then the JSON should have 3 keys
Then the JSON should have 4 whatevers
```

All instances of "should" above could be followed by "not" and all instances of "JSON" could be downcased and/or followed by "response."

### Table Format
Another step exists that uses Behat's table formatting and wraps two of the above steps:

Then the JSON should have the following:

```
  | path/0 | {"key":"value"}   |
  | path/1 | ["entry","entry"] |
```

Any number of rows can be given. The step above is equivalent to:

```
Then the JSON at "path/0" should be {"key":"value"}
And the JSON at "path/1" should be ["entry","entry"]
```

If only one column is given:

```
Then the JSON should have the following:
  | path/0 |
  | path/1 |
```

This is equivalent to:

```
Then the JSON should have "path/0"
And the JSON should have "path/1"
```

### JSON Memory
There's one more Behat step that json_spec provides which hasn't been used above. It's used to memorize JSON for reuse in later steps. You can "keep" all or a portion of the JSON by giving a name by which to remember it.

```
Feature: User API
  Scenario: Index action includes full user JSON
    Given the following user exists:
      | id | first_name | last_name |
      | 1  | Steve      | Richert   |
    And I visit "/users/1.json"
    And I keep the JSON response as "USER_1"
    When I visit "/users.json"
    Then the JSON response should be:
      """
      [
        {$USER_1}
      ]
      """
```

You can memorize JSON at a path:

```
Given I keep the JSON response at "first_name" as "FIRST_NAME"
```

You can remember JSON at a path:

```
Then the JSON response at "0/first_name" should be:
  """
  {$FIRST_NAME}
  """
```

You can also remember JSON inline:

```
Then the JSON response at "0/first_name" should be {$FIRST_NAME}
```

Also starting from version `0.2.3` you can inject memory helper into your feature context to define some variables. To do so, your context should implement `MemoryHelperAware` interface.

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

