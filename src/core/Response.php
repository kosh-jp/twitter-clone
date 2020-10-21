<?php

class Response
{
    /** @var string */
    protected $content;
    /** @var int */
    protected $status_code = 200;
    /** @var string */
    protected $status_text = 'OK';
    /** @var array<string,string> */
    protected $http_header = [];

    /**
     * Echo http response
     *
     * @return void
     */
    public function send(): void
    {
        header('HTTP/1.1 ' . $this->status_code . ' ' . $this->status_text);

        foreach ($this->http_header as $name => $value) {
            header($name . ': ' . $value);
        }

        echo $this->content;
    }

    /**
     * Set the content
     *
     * @param string $content
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }


    /**
     * Set the status code and status text
     *
     * @param int $status_code
     * @param string $status_text
     * @return void
     */
    public function setStatusCode(int $status_code, string $status_text = ''): void
    {
        $this->status_code = $status_code;
        $this->status_text = $status_text;
    }

    /**
     * Set the http header
     *
     * @param string $name
     * @param string $value
     * @return void
     */
    public function setHttpHeader(string $name, string $value): void
    {
        $this->http_header[$name] = $value;
    }
}
