<?php

namespace classes;

class Response {
    public  int $code;
    public mixed $data;
    public bool $success;

    /**
     * @param int $code
     * @param mixed $data
     */
    public function __construct(int $code, mixed $data) {
        $this->code = $code;
        $this->data = $data;
        $this->success = $code === 1;
    }



}
