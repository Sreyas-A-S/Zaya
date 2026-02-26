<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


use OpenApi\Attributes as OA;

#[OA\Info(title: "Zaya API Documentation", version: "1.0.0", description: "API documentation for Zaya Platform")]
#[OA\Server(url: "/api", description: "Main API Server")]
#[OA\SecurityScheme(securityScheme: "apiKeyAuth", type: "apiKey", name: "X-API-KEY", in: "header")]
abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
