<?php

namespace App\Markdown\Util;

use Illuminate\Contracts\Support\Htmlable;
use Stringable;

class ComponentSlot implements Htmlable, Stringable
{
    /**
     * The slot contents.
     *
     * @var string
     */
    protected $contents;

    /**
     * Create a new slot instance.
     *
     * @param  string  $contents
     * @param  array  $attributes
     */
    public function __construct($contents = '', $attributes = [])
    {
        $this->contents = $contents;
    }

    /**
     * Get the slot's HTML string.
     *
     * @return string
     */
    public function toHtml()
    {
        return $this->contents;
    }

    /**
     * Get the slot's HTML string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toHtml();
    }
}
