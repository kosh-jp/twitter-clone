<?php

class View
{
    /** @var string */
    protected $base_dir;
    /** @var array<string,string> */
    protected $defaults;
    /** @var array<string,string> */
    protected $layout_variables = [];

    /**
     * Constructor method
     *
     * @param string $base_dir
     * @param array<string,mixed> $defaults
     */
    public function __construct(string $base_dir, array $defaults = [])
    {
        $this->base_dir = $base_dir;
        $this->defaults = $defaults;
    }

    /**
     * Set layout variables
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setLayoutVar(string $name, $value): void
    {
        $this->layout_variables[$name] = $value;
    }

    /**
     * Get the string contents
     *
     * @param string $_path
     * @param array<string,string> $_variables
     * @param string $_layout
     * @throws HttpNotFoundException
     * @return string
     */
    public function render(string $_path, array $_variables = [], $_layout = ''): string
    {
        $_file = $this->base_dir . '/' . $_path . '.php';

        $_variables = array_merge($this->defaults, $_variables);
        extract($_variables);

        ob_start();
        ob_implicit_flush(0);

        require $_file;

        $content = ob_get_clean();

        if (!$content) {
            throw new HttpNotFoundException('Not found view file: ' . $_file);
        }

        if ($_layout) {
            $_layout_variables = array_merge($this->layout_variables, ['_content' => $content]);
            $content = $this->render($_layout, $_layout_variables);
        }

        return $content;
    }

    /**
     * Encode HTML special characters
     *
     * @param string $string
     * @return string
     */
    public function escape($string): string
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
