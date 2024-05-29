<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;


class AuthController extends Controller
{    
    /**
     * Get Register
     * @OA\Post (
     *     path="/api/register",
     *     tags={"Task"},
     *     @OA\RequestBody(
     *         description="Register here",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     description="User Name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="User Email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="User password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="c_password",
     *                     description="Confirm password",
     *                     type="string"
     *                 ),
     *                 required={"name", "email", "password", "c_password"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function register(Request $request){
        // validation
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        // invalid request for user updation
        if($validator->fails()){
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }
        
        // user password bcrypte and user creation method
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        
        // generating the token using core method
        $success['token'] = $user->createToken("MyApp")->plainTextToken;
        $success['name'] = $user->name;
        
        // success response object
        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'User registered successfully'
        ];
        return response()->json($response, 200);
    }
    
    /**
     * Get Login
     * @OA\Post (
     *     path="/api/login",
     *     tags={"Task"},
     *     @OA\RequestBody(
     *         description="Login here",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="User Email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="User password",
     *                     type="string"
     *                 ),
     *                 required={"email", "password"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function login(Request $request){
        //login auth function to login the user
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] = $user->createToken("MyApp")->plainTextToken;
            $success['name'] = $user->name;
            
            // success response object
            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User login successfully'
            ];
            return response()->json($response, 200);
        } else {
            // failure response object
            $response = [
                'success' => false,
                'message' => 'Unauthorised'
            ];
            return response()->json($response, 400);
        }

    }
}
