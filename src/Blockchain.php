<?php

declare(strict_types=1);

namespace Blockchain;

use InvalidArgumentException;

final class Blockchain
{
    /**
     * @var Block[]
     */
    private $blocks = [];

    public function __construct(Block $genesisBlock)
    {
        $this->blocks[] = $genesisBlock;
    }

    public function add(Block $block): void
    {
        if (! $this->last()->isNextValid($block)) {
            throw new InvalidArgumentException(sprintf('Given block %s is not valid next block', $block->hash()));
        }

        $this->blocks[] = $block;
    }

    public function isValid(): bool
    {
        if (! $this->blocks[0]->isEqual(Block::genesis())) {
            return false;
        }

        $count = count($this->blocks) - 1;
        for ($i = 0; $i < $count; ++$i) {
            if (! $this->blocks[$i]->isNextValid($this->blocks[$i + 1])) {
                return false;
            }
        }

        return true;
    }

    private function last(): Block
    {
        return end($this->blocks);
    }
}
