<?php

namespace JsonSpec\PhpSpec\Runner\Maintainer;

use JsonSpec\Helper\FileHelper;

interface FileHelperAware
{

    /**
     * @param  FileHelper $helper
     * @return mixed
     */
    public function setFileHelper(FileHelper $helper);

}
