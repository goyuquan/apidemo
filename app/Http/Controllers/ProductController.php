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
        $options = DB::select("
                    select distinct COLUMN_NAME
                    from information_schema.columns
                    where TABLE_SCHEMA='api'
                    ");

        $option_values = array();
        foreach($options as $value) {
            array_push($option_values, $value);
            return $option_values;
        }


        $message = [
            'name.required' => '名称必填',
            'name.unique' => '名称已经存在了',
            'price.required' => '价格必填',
            'status.required' => '状态必填',
            'unit.required' => '单位必填',
            'origin.required' => '产地必填',
            'describe.required' => '描述必填'
        ];
        $this->validate($request, [
            'name' => 'required|unique:products',
            'price' => 'required',
            'status' => 'required',
            'unit' => 'required',
            'origin' => 'required',
            'describe' => 'required'
        ], $message);

        try {
            $data = Product::find($id);

            $data->name = $request->input('name');
            $data->price = $request->input('price');
            $data->status = $request->input('status');
            $data->unit = $request->input('unit');
            $data->origin = $request->input('origin');
            $data->img_id = $request->input('img_id');
            $data->describe = $request->input('describe');

            $msg = '更新成功';
            $statusCode = 200;
        } catch(\Exception $e) {
            $msg = '更新b出了问题';
            $statusCode = 500;
        }

        return response()->json([ 'message' => $msg ], $statusCode );
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
