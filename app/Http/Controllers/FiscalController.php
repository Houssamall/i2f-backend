<?php

namespace App\Http\Controllers;

use App\Models\Fiscal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class FiscalController extends Controller
{
    /**
     * Create a new FiscalController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get all fiscal records for the authenticated user.
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

            $fiscals = Fiscal::where('id_User', $userId)->get();

            return response()->json($fiscals);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving fiscals'], 500);
        }
    }

    /**
     * Get fiscal records for a specific user ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFiscal($id)
    {
        try {
            $fiscals = Fiscal::where('id_User', $id)->get();

            return response()->json($fiscals);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving fiscals'], 500);
        }
    }

    /**
     * Store multiple fiscal records.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
{
    try {
        $fiscals = $request->all();

        if (empty($fiscals)) {
            return response()->json(['error' => 'No fiscal data provided.'], 400);
        }

        // Get the id_User from the first fiscal object
        $idUser = $fiscals[0]['id_User'];

        // Begin transaction
        DB::beginTransaction();

        // Delete existing records for this user
        Fiscal::where('id_User', $idUser)->delete();

        // Create new records
        $createdFiscals = [];
        foreach ($fiscals as $fiscalData) {
            // Check each field, and if it's empty, set it to "0"
            $fiscal = Fiscal::create([
                'mois' => $fiscalData['mois'] ?? '0',
                'tvaN' => $fiscalData['tvaN'] ?? '0',
                'tvaN1' => $fiscalData['tvaN1'] ?? '0',
                'tvaVAR' => $fiscalData['tvaVAR'] ?? '0',
                'irN' => $fiscalData['irN'] ?? '0',
                'irN1' => $fiscalData['irN1'] ?? '0',
                'irVAR' => $fiscalData['irVAR'] ?? '0',
                'isN' => $fiscalData['isN'] ?? '0',
                'isN1' => $fiscalData['isN1'] ?? '0',
                'isVAR' => $fiscalData['isVAR'] ?? '0',
                'id_User' => $idUser
            ]);
            $createdFiscals[] = $fiscal;
        }

        DB::commit();

        return response()->json($createdFiscals);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Error creating fiscals: ' . $e->getMessage()], 500);
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
