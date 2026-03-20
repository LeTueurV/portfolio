<?php

/**
 * @OA\Info(
 *   title="Portfolio API",
 *   version="1.0.0"
 * )
 *
 * @OA\PathItem(
 *   path="/api/health",
 *   @OA\Get(
 *     summary="Health check",
 *     @OA\Response(response=200, description="OK")
 *   )
 * )
 */

// This file provides a minimal OpenAPI annotation so swagger-php finds at least
// one PathItem when running `php artisan l5-swagger:generate`.
