<?php

namespace WonderWp\Component\BlockType\Response;

class BlockTypeRegistrationResponse extends AbstractResponse implements BlockTypeRegistrationResponseInterface
{
    protected ?WP_Taxonomy $wpRegistrationResult = null;

    public function getWpRegistrationResult(): ?WP_Taxonomy
    {
        return $this->wpRegistrationResult;
    }

    public function setWpRegistrationResult(?WP_Taxonomy $wpRegistrationResult): void
    {
        $this->wpRegistrationResult = $wpRegistrationResult;
    }
}
