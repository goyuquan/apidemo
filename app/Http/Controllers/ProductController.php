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
      $response = array();
      try {
        $products = DB::table('products')
        ->leftJoin('options', 'products.origin', '=', 'options.id')
        ->get([
          'products.id',
          'products.status',
          'products.name',
          'products.price',
          'products.unit',
          'products.img_id',
          'products.created_at',
          'products.updated_at',
          'options.option as origin'
        ]);
        $statusCode = 200;
        $response['data'] = $products;
      } catch (\Exception $e) {
        $statusCode = 404;
        $response['message'] = "找不到资源";
      }
      return response()->json($response, $statusCode);
    }


    public function update(Request $request, $id)
    {

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
        $options = DB::select("
        select distinct COLUMN_NAME
        from information_schema.columns
        where TABLE_SCHEMA='api'
        ");
        $option_values = array();

        try {
            foreach($options as $value) {
                array_push($option_values, $value->COLUMN_NAME );
            }

            $products = Product::findOrFail($productId)->first();
            $product = array_add($products, 'options', $option_values);
            $statusCode = 200;
            return response()->json([ 'data' => $product], $statusCode );
        } catch (Exception $e) {
            $user = null;
            $statusCode = 404;
            return response()->json([ 'message' => $e ], $statusCode );
        }
    }
}
