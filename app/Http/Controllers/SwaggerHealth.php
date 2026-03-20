<?php
namespace App\Http\Controllers;

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
class SwaggerHealth extends Controller
{
    // no methods required; annotations are scanned statically
}
