<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(title="Envoitures Rest Api", version="0.1")
 */
class Controller extends BaseController
{

    /**
     * @OA\OpenApi(
     *     @OA\Info(
     *         version="1.0",
     *         title="Envoitures Api",
     *         description="L'api rest laravel pour la platform envoitures",
     *     )
     * )
     */

    use AuthorizesRequests, ValidatesRequests;

}
