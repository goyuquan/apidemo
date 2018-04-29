<?php
namespace App\Http\Controllers;
use App\User;
use App\Product;
use Validator;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;


class ProductController extends Controller
{

    public function index()
    {
        $products = DB::table('products')->select(
            'id',
            'status',
            'name',
            'price',
            'unit',
            'origin',
            'img_id',
            'created_at',
            'updated_at'
            )->get();
        return response()->json([
            'data' => $products->toArray()
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:products',
            'price' => 'required',
            'status' => 'required',
            'unit' => 'required',
            'origin' => 'required',
            'describe' => 'required'
        ]);

        $data = App\Product::find($id);

        $data->phone => $request->input('phone'),
        $data->price => $request->input('price'),
        $data->status => $request->input('status'),
        $data->unit => $request->input('unit'),
        $data->origin => $request->input('origin'),
        $data->img_id => $request->input('img_id'),
        $data->describe => $request->input('describe'),

        // if ($data->save()) {
            $statusCode = $data->save() ? 200 : 500;
        // }

        return response()->json([
                'STATUS' => '$user',
            ], $statusCode );
    }


    public function show($productId)
    {
        try {
            $product = Product::findOrFail($productId)->first();
            $statusCode = 200;
            return response()->json([ 'data' => $product], $statusCode );
        } catch (Exception $e) {
            $user = null;
            $statusCode = 404;
            return response()->json([ 'message' => $e ], $statusCode );
        }

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
