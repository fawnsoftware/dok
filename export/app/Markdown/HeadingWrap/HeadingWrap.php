<?php

namespace App\Markdown\HeadingWrap;

use League\CommonMark\Node\Inline\AbstractInline;

class HeadingWrap extends AbstractInline
{
    public function __construct()
    {
        parent::__construct();
    }
}
