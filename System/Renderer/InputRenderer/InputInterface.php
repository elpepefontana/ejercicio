<?php

namespace System\Renderer\InputRenderer;

interface InputInterface
{
    public function render(): string;
    
    public function data(): array;
}
