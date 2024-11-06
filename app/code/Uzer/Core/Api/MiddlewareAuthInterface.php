<?php

namespace Uzer\Core\Api;

interface MiddlewareAuthInterface
{

    public function auth(array $data = []);

}
