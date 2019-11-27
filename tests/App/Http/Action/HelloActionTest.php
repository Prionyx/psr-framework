<?php

namespace Tests\App\Http\Action\Blog;

use App\Http\Action\HelloAction;
use Framework\Template\TemplateRender;
use PHPUnit\Framework\TestCase;

class HelloActionTest extends TestCase
{
    private $renderer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->renderer = new TemplateRender('templates');
    }

    public function test()
    {
        $action = new HelloAction($this->renderer);

        $response = $action();

        self::assertStringContainsString('Hello', $response->getBody()->getContents());
    }
}
