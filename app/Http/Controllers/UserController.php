<?php
namespace App\Http\Controllers;
use App\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;


class UserController extends Controller
{

    public function index()
    {
        return response()->json(['data' => User::all()->toArray()], 200);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|unique:users',
            'password' => 'required'
        ]);

        $data = [
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password'))
        ];

        $user = User::create($data);
        $statusCode = $user ? 200 : 422;
        return response()->json([
                'data' => $user,
            ], $statusCode );
    }


    public function show($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $statusCode = 200;
            $orders = $user->orders;
        } catch (\Exception $e) {
            $user = null;
            $statusCode = 404;
        }

        return response()->json([
                'data' => [
                    'user' => $user,
                    'orders' => $orders
                    ],
                'message' => $user ? "success" : "Not found.",
            ], $statusCode );
    }


    public function update(Request $request, $userId)
    {
        try {
            $user = self::userExist($userId);
            $user->update($request->only('name', 'password'));
            $statusCode = 200;
        } catch(\Exception $e) {
            $user = null;
            $statusCode = 404;
        }

        return response([
                "data" => $user,
                "status" => $user ? "success" : "Not found."
            ], $statusCode);
    }


    public function delete($userId)
    {
        try {
            $user = self::userExist($userId);
            $user->delete();
            $statusCode = 200;
        } catch(\Exception $e) {
            $user = null;
            $statusCode = 404;
        }

        return response([
                "data" => $user,
                "status" => $user ? "success" : "Not found."
            ], $statusCode );
    }


    protected static function userExist($id)
    {
        return User::findOrFail($id);
    }


    public function login(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('phone', $request->input('phone'))->first();

        if(Hash::check($request->input('password'), $user->password)){
            $token = base64_encode(str_random(40));
            User::where('phone', $request->input('phone'))
                        ->update(['remember_token' => $token]);

            return response()->json([
                            'status' => 'success',
                            'data' => $user], 200)
                            ->withHeaders([
                                'Authorization' => $token,
                            ]);
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => '账号密码不匹配'
            ], 401);
        }
    }


    public function logout($id)
    {
        if(User::where('id', $id)->update(['remember_token' => '']))
        {
            return response()->json(['status' => 'success'], 200);
        }
    }

}
