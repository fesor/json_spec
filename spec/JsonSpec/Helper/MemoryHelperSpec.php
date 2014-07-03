<?php

namespace spec\JsonSpec\Helper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MemoryHelperSpec extends ObjectBehavior
{

    public function it_has_memory()
    {
        $this->getRemembered()->shouldBeArray();
    }

    public function it_memorizes_strings()
    {
        $this->memorize('key', 'value');
        $this->getRemembered()->shouldBeLike(array(
            'key' => 'value'
        ));
    }

    public function it_regurgitates_unremembered_strings()
    {
        $this->remember('json_{$key}')->shouldBe('json_{$key}');
    }

    public function it_remembers_strings()
    {
        $this->memorize('key', 'spec');
        $this->remember('json_{$key}')->shouldBe('json_spec');
    }

    public function it_forgets()
    {
        $this->memorize('key', 'value');
        $this->forget();
        $this->getRemembered()->shouldBeLike(array());
    }

}
