<?php

namespace App\Http\Controllers;

use App\Models\Financier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class FinancierController extends Controller
{
    /**
     * Create a new FinancierController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get all financier records for the authenticated user.
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

            $financiers = Financier::where('id_User', $userId)->get();

            return response()->json($financiers);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving financiers'], 500);
        }
    }

    /**
     * Get financier records for a specific user ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFinancier($id)
    {
        try {
            $financiers = Financier::where('id_User', $id)->get();

            return response()->json($financiers);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving financiers'], 500);
        }
    }

    /**
     * Store multiple financier records.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
{
    try {
        $financiers = $request->all();

        if (empty($financiers)) {
            return response()->json(['error' => 'No financier data provided.'], 400);
        }

        // Get the Id_User from the first financier object
        $idUser = $financiers[0]['id_User'];

        // Begin transaction
        DB::beginTransaction();

        // Delete existing records for this user
        Financier::where('id_User', $idUser)->delete();

        // Create new records
        $createdFinanciers = [];
        foreach ($financiers as $financierData) {
            // Check each field, and if it's empty, set it to "0"
            $financier = Financier::create([
                'designation' => $financierData['designation'] ?? '0',
                'exN' => $financierData['exN'] ?? '0',
                'exN1' => $financierData['exN1'] ?? '0',
                'var' => $financierData['var'] ?? '0',
                'id_User' => $idUser
            ]);
            $createdFinanciers[] = $financier;
        }

        DB::commit();

        return response()->json($createdFinanciers);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Error creating financiers: ' . $e->getMessage()], 500);
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