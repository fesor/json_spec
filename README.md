Json Spec
===================

Easily handle JSON-response assertions.

If you working with JSON-based REST APIs there are several issues:

- You can't simple check is a response is equal to given string as there is things like server-generated IDs
- Matching the whole responses breaks DRY for the spec

This library is an set of tool for flexible  JSON, which aims to solve those problems.

## PhpSpec

To use new set of matchers in your PhpSpec suites, just add JsonSpec extension in your `phpspec.yml`:
```
extensions:
   - JsonSpec\PhpSpec\Extension
```

That's all you need to do to start use this new matchers:
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

## Inspired By
- [json_spec](https://github.com/collectiveidea/json_spec) - Ruby's gem for handling JSON with RSpec and Cucumber
 

