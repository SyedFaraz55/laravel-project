<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
/**
 * @OA\OpenApi(
 *   @OA\Info(
 *     title="sooandcarrots-api",
 *     version="1.0.0",
 *     description="This is a simple example of a OpenAPI definition",
 *     @OA\Contact(
 *       email="support@example.com"
 *     )
 *   ),
 *   @OA\Server(
 *     description="API Server",
 *     url="http://sooandcarrots/api"
 *   )
 * )
 */
class UserController extends Controller{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Get list of users",
     *     description="Returns list of users",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

     /**
     * @OA\Get(
     *     path="/api/user/pagination",
     *     tags={"Users"},
     *     summary="Get 10 users at a time",
     *     description="Returns list of 10 users ",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
     */
    public function list()
    {
        $users = User::paginate(10);
        return response()->json($users);
    }


    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Usually not used for APIs, used in forms
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
  
     public function store(Request $request)
    {
        $user = User::create($request->all());
        return response()->json($user, 201);
    }

    /**
     * Add a like to another user.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    
    public function updateReactions(Request $request, User $user)
    {
        // Validate the incoming request to check the action type (like or dislike)
        $data = $request->validate([
            'action' => 'required|string|in:like,dislike', // Action must be 'like' or 'dislike'
        ]);
    
        try {
            if ($data['action'] === 'like') {
                $user->increment('likes'); // Increment likes
            } elseif ($data['action'] === 'dislike') {
                $user->increment('dislikes'); // Increment dislikes (assuming 'dislike' means increase dislike count)
            }
    
            // Return a success response
            return response()->json([
                'message' => 'User preferences updated successfully.',
                'user' => $user->fresh()
            ], 200);
        } catch (\Exception $e) {
            // Handle any possible errors
            return response()->json(['message' => 'Failed to update user preferences due to an unexpected error'], 500);
        }
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Usually not used for APIs, used in forms
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return response()->json($user);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(null, 204);
    }
}
