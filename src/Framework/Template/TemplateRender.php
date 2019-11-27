<?php

namespace Framework\Template;

interface TemplateRender
{
    public function render($name, array $params = []): string;
}
