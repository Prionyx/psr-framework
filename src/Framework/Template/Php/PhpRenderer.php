<?php

namespace Framework\Template\Php;

use Framework\Template\TemplateRender;

class PhpRenderer implements TemplateRender
{
    /**
     * @var Extension[]
     */
    private $extensions = [];
    private $path;
    private $extend;
    private $blocks = [];
    private $blockNames;
    private $router;

    public function __construct($path)
    {
        $this->path = $path;
        $this->blockNames = new \SplStack();
    }

    public function addExtension(Extension $extension): void
    {
        $this->extensions[] = $extension;
    }

    public function render($name, array $params = []): string
    {
        $level = ob_get_level();
        $templateFile = $this->path . '/' . $name . '.php';
        $this->extend = null;

        try {
            ob_start();
            extract($params, EXTR_OVERWRITE);
            require $templateFile;
            $content = ob_get_clean();
        } catch (\Throwable|\Exception $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }
            throw $e;
        }

        if (!$this->extend) {
            return $content;
        }

        return $this->render($this->extend);
    }

    public function extend($view): void
    {
        $this->extend = $view;
    }

    public function block($name, $content): void
    {
        if ($this->hasBlock($name)) {
            return;
        }
        $this->blocks[$name] = $content;
    }

    public function ensureBlock($name): bool
    {
        if ($this->hasBlock($name)) {
            return false;
        }
        $this->beginBlock($name);
        return true;
    }

    public function beginBlock($name): void
    {
        $this->blockNames->push($name);
        ob_start();
    }

    public function endBlock(): void
    {
        $content = ob_get_clean();
        $name = $this->blockNames->pop();
        if ($this->hasBlock($name)) {
            return;
        }
        $this->blocks[$name] = $content;
    }

    public function renderBlock($name): string
    {
        $block = $this->blocks[$name] ?? null;

        if ($block instanceof \Closure) {
            return $block();
        }

        return $block ?? '';
    }

    private function hasBlock($name): bool
    {
        return array_key_exists($name, $this->blocks);
    }

    public function encode($string): string
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE);
    }

    public function __call($name, $arguments)
    {
        foreach ($this->extensions as $extension) {
            $functions = $extension->getFunctions();
            if (array_key_exists($name, $functions)) {
                return $functions[$name](...$arguments);
            }
            throw new \InvalidArgumentException('Undefined function "' . $name . '"');
        }
    }
}
