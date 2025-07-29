<?php

namespace App\Http\Controllers;

use App\Models\Social;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class SocialController extends Controller
{
    /**
     * Create a new SocialController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get all social records for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $userId = $this->getUserIdFromToken(request()->header('Authorization'));

            if (!$userId) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $socials = Social::where('id_User', $userId)->get();

            return response()->json($socials);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving socials'], 500);
        }
    }

    /**
     * Get social records for a specific user ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSocial($id)
    {
        try {
            $socials = Social::where('id_User', $id)->get();

            return response()->json($socials);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving socials'], 500);
        }
    }

    /**
     * Store multiple social records.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
{
    try {
        $socials = $request->all();

        if (empty($socials)) {
            return response()->json(['error' => 'No social data provided.'], 400);
        }

        // Get the id_user from the first social object
        $idUser = $socials[0]['id_User'];

        // Begin transaction
        DB::beginTransaction();

        // Delete existing records for this user
        Social::where('id_User', $idUser)->delete();

        // Create new records
        $createdSocials = [];
        foreach ($socials as $socialData) {
            // Check each field, and if it's empty, set it to "0"
            $social = Social::create([
                'mois' => $socialData['mois'] ?? '0',
                'masseN' => $socialData['masseN'] ?? '0',
                'masseN1' => $socialData['masseN1'] ?? '0',
                'masseVAR' => $socialData['masseVAR'] ?? '0',
                'cnssN' => $socialData['cnssN'] ?? '0',
                'cnssN1' => $socialData['cnssN1'] ?? '0',
                'cnssVAR' => $socialData['cnssVAR'] ?? '0',
                'id_User' => $idUser
            ]);
            $createdSocials[] = $social;
        }

        DB::commit();

        return response()->json($createdSocials);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Error creating socials: ' . $e->getMessage()], 500);
    }
}


    /**
     * Extract user ID from JWT token.
     *
     * @param string|null $authorization
     * @return int|null
     */
    private function getUserIdFromToken(?string $authorization)
    {
        if (!$authorization || !str_starts_with($authorization, 'Bearer ')) {
            return null;
        }

        try {
            $token = str_replace('Bearer ', '', $authorization);
            $payload = JWTAuth::parseToken()->getPayload();
            return $payload->get('sub'); // 'sub' claim contains the user ID in JWT
        } catch (JWTException $e) {
            return null;
        }
    }
}
