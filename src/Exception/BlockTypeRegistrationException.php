<?php

namespace WonderWp\Component\BlockType\Exception;

use WonderWp\Component\Response\Traits\HasWpError;

class BlockTypeRegistrationException extends \Exception
{
    use HasWpError;
} 