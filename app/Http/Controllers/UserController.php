<?php
namespace App\Http\Controllers;
use App\User;
use Validator;
use Illuminate\Http\Request;
//use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\Hash;
/**
* Class UserController
*/
class UserController extends Controller
{
  /**
  * Get all users
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function index()
  {
    return response(['data' => User::all()->toArray()]);
  }
  /**
  * Create a new user resource.
  *
  * @param Request $request request
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function store(Request $request)
  {
    $this->validate($request, [
        'name' => 'required',
        'email' => 'required|unique:users|email',
        'password' => 'required'
      ]
    );

    $data = [
       'name' => $request->input('name'),
      'email' => $request->input('email'),
      'password' => Hash::make($request->input('password'))
    ];

    $user = User::create($data);
    $statusCode = $user ? 200 : 422;
    return response(
      [
        'data' => $user,
        'status' => $user ? "success" : "error",
      ], $statusCode
    );
  }
  /**
  * Get one user from the database
  *
  * @param int $userId userId
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function show($userId)
  {
    try {
      $user = User::findOrFail($userId);
    } catch (\Exception $e) {
      $user = null;
      $statusCode = 404;
    }
    return response(
      [
        'data' => $user,
        'status' => $user ? "success" : "Not found.",
      ], $statusCode ?? 201
    );
  }
  /**
  * Implement a full/partial update
  *
  * @param Request $request request
  * @param int     $userId  userId
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function update(Request $request, $userId)
  {
    try {
      $user = self::userExist($userId);
      $user->update($request->only('name', 'password'));
    } catch(\Exception $e) {
      $user = null;
      $statusCode = 404;
    }
    return response(
      [
        "data" => $user,
        "status" => $user ? "success" : "Not found."
      ], $statusCode ?? 200
    );
  }
  /**
  * Delete a resource
  *
  * @param int $userId userId
  *
  * @return \Illuminate\Http\JsonResponse
  */
  public function delete($userId)
  {
    try {
      $user = self::userExist($userId);
      $user->delete();
    } catch(\Exception $e) {
      $user = null;
      $statusCode = 404;
    }
    return response(
      [
        "data" => $user,
        "status" => $user ? "success" : "Not found."
      ], $statusCode ?? 200
    );
  }
  /**
  * Check if user exist by id
  *
  * @param int $id id
  *
  * @return bool|mixed|static
  */
  protected static function userExist($id)
  {
    return User::findOrFail($id);
  }


  public function authenticate(Request $request)
   {
       $this->validate($request, [
           'email' => 'required',
           'password' => 'required'
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if(Hash::check($request->input('password'), $user->password)){
            $apikey = base64_encode(str_random(40));
            User::where('email', $request->input('email'))->update(['api_key' => "$apikey"]);
            return response()->json(['status' => 'success','api_key' => $apikey, 'data' => $user]);
        }else{
            return response()->json(['status' => 'fail'],401); 
        }
    }

  public function logout($id)
    {
        if(User::where('id', $id)->update(['api_key' => '']))
	{
		 return response()->json(['status' => 'success'], 200);
	}
	
    }
}
