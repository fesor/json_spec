<?php

namespace spec\JsonSpec\Helper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExclusionHelperSpec extends ObjectBehavior
{

    public function it_should_exclude_keys()
    {
        $data = (object) array(
            'id' => 1,
            'collection' => array(
                (object) array(
                    'id' => 1,
                    'json' => 'spec'
                )
            )
        );

        $this->excludeKeys($data)->shouldBeLike((object) array(
            'collection' => array(
                (object) array(
                    'json' => 'spec'
                )
            )
        ));
    }

}
