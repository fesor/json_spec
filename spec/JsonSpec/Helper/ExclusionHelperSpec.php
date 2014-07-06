<?php

namespace spec\JsonSpec\Helper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExclusionHelperSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith(array('id'));
    }

    public function it_should_exclude_keys_defined_in_settings()
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

    public function it_should_exclude_user_defined_keys()
    {
        $data = (object) array(
            'id' => 1,
            'created_at' => time()
        );

        $this->excludeKeys($data, array('created_at'))->shouldBeLike((object) array(
            'id' => 1
        ));
    }

}
