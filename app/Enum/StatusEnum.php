<?php

namespace App\Enum;

class StatusEnum
{
    const OK = 200;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const UNPROCESSABLE = 422;
    const SERVER_ERROR = 500;
}