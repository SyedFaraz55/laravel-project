<?php


namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\UserLike;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\MailableName;
use Illuminate\Support\Facades\Mail;
class UserLikeController extends Controller
{
    /**
     * Add a like to another user.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */

 

/**
 * @OA\Post(
 *   path="/api/users/likes",
 *   operationId="updateUserPreferences",
 *   tags={"User Likes"},
 *   summary="Update user likes or dislikes",
 *   description="Allows updating the likes or dislikes count for a specified user based on the action provided.",
 *   @OA\Parameter(
 *     name="user_id",
 *     in="path",
 *     required=true,
 *     description="The ID of the user whose preferences are to be updated",
 *     @OA\Schema(
 *       type="integer"
 *     )
 *   ),
 *   @OA\RequestBody(
 *     required=true,
 *     description="The action to perform (like or dislike)",
 *     @OA\JsonContent(
 *       type="object",
 *       required={"action"},
 *       @OA\Property(
 *         property="action",
 *         type="string",
 *         enum={"like", "dislike"},
 *         description="Action to perform on user's preferences"
 *       )
 *     )
 *   ),
 *   @OA\Response(
 *     response=200,
 *     description="Preferences updated successfully",
 *     @OA\JsonContent(
 *       type="object",
 *       @OA\Property(
 *         property="message",
 *         type="string",
 *         example="User preferences updated successfully."
 *       ),
 *       @OA\Property(
 *         property="user",
 *         type="object",
 *         @OA\Property(
 *           property="likes",
 *           type="integer",
 *           description="Total number of likes"
 *         ),
 *         @OA\Property(
 *           property="dislikes",
 *           type="integer",
 *           description="Total number of dislikes"
 *         )
 *       )
 *     )
 *   ),
 *   @OA\Response(
 *     response=400,
 *     description="Invalid request parameters",
 *     @OA\JsonContent(
 *       type="object",
 *       @OA\Property(
 *         property="message",
 *         type="string",
 *         example="Invalid action type provided."
 *       )
 *     )
 *   ),
 *   @OA\Response(
 *     response=500,
 *     description="Server error",
 *     @OA\JsonContent(
 *       type="object",
 *       @OA\Property(
 *         property="message",
 *         type="string",
 *         example="Failed to update user preferences due to an unexpected error"
 *       )
 *     )
 *   )
 * )
 */
    public function store(Request $request, User $user)
    {


        // Validate the incoming request to check the action type (like or dislike)
        $data = $request->validate([
            'action' => 'required|string|in:like,dislike', // Action must be 'like' or 'dislike'
        ]);

        $body = $request->all();

        $user = User::findOrFail($body['liked_user_id']);
        try {
            if ($data['action'] === 'like') {
                $user->increment('likes'); // Increment likes
                if($user->likes == 5) {
                    Mail::to('sdfahad729@gmail.com')->send(new MailableName());
                }
                $like = UserLike::create([
                    "user_id" => $user['id'],
                    "liked_user_id" => $body['liked_user_id']
                ]);
                return response()->json($like, 201);
            } elseif ($data['action'] === 'dislike') {
                $user->increment('dislikes'); // Increment dislikes (assuming 'dislike' means increase dislike count)
                UserLike::destroy($body['liked_user_id']);
                return response()->json(null, 204);
            }
    
            // Return a success response
            return response()->json([
                'message' => 'User preferences updated successfully.',
                'user' => $user->fresh()
            ], 200);
        } catch (\Exception $e) {
            // Handle any possible errors
            return response()->json(['message' => $e], 500);
        }
    } 

    /**
     * Display the likes for a specific user.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        $likes = UserLike::where('user_id', $user_id)->get();
        return response()->json($likes);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
 * @OA\Get(
 *   path="/api/user-likes",
 *   operationId="listUserLikes",
 *   tags={"User Likes"},
 *   summary="Get all user likes",
 *   description="Retrieves a list of all user likes.",
 *   @OA\Response(
 *     response=200,
 *     description="Successful operation",
 *     @OA\JsonContent(
 *       type="array",
 *       @OA\Items(
 *         type="object",
 *         @OA\Property(
 *           property="user_id",
 *           type="integer",
 *           description="ID of the user"
 *         ),
 *         @OA\Property(
 *           property="likes",
 *           type="integer",
 *           description="Number of likes"
 *         )
 *       )
 *     )
 *   ),
 *   @OA\Response(
 *     response=404,
 *     description="No likes found"
 *   )
 * )
 */
    public function list()
    {
        $userLikes = UserLike::with(['liker', 'liked'])->paginate(10);
        return response()->json($userLikes);
    }

    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(null, 204);
    }
}
