<?php

namespace spec\JsonSpec;

use PhpSpec\ObjectBehavior;

class MatcherOptionsFactorySpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith(array('id'));
    }

    public function it_provides_options()
    {
        $this->createOptions()->shouldReturnAnInstanceOf('JsonSpec\\MatcherOptions');
    }

    public function it_allow_to_specify_default_options()
    {
        $options = $this->createOptions();
        $options->getExcludedKeys()->shouldBeLike(array('id'));
    }

}
