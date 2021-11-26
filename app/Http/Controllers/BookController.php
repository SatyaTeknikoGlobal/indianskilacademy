<?php

namespace App\Http\Controllers;

use App\Coupon;
use App\Order;
use Illuminate\Http\Request;
use JWTAuth;
use App\Address;
use Validator;
use App\Author;
use App\BookBanner;
use App\Book;
use App\BookCategory;
use App\User;
use App\Cart;
class BookController extends Controller
{
    
    private function getAdminImageUrl($url,$image){

        return "https://iasgyan.org/public/images/".$url.'/'.$image;

    }

    public function home(Request $request)
    {
        $validator =  Validator::make($request->all(), [

            'token' => 'required',

        ]);

        $content = array();

        $current_timestamp = time();

        $user = null;

        if ($validator->fails()) {

            return response()->json([

                'result' => 'failure',

                'message' => json_encode($validator->errors()),

                'content' =>$content

            ],400);

        }

        $user = JWTAuth::parseToken()->authenticate();

        if (empty($user)){

            return response()->json([

                'result' => 'failure',

                'message' => '',

                'content' =>$content

            ],401);

        }


        $bookbanner = BookBanner::latest()->get();

        foreach ($bookbanner as $row) {

        $row->banner = $this->getAdminImageUrl('bookbanner',$row->banner);

        }
        $hard_copy_category =BookCategory::select(['book_categories.*'])->leftjoin('books','books.category','=','book_categories.id')->where(['books.type'=>'hard_copy','book_categories.status'=>1])->distinct()->get(); 

        foreach ($hard_copy_category as $row) {
        $row->image = $this->getAdminImageUrl('category',$row->image);
        }
        $ebook_category =  BookCategory::select(['book_categories.*'])->leftjoin('books','books.category','=','book_categories.id')->where(['books.type'=>'ebook','book_categories.status'=>1])->distinct()->get();   

        foreach ($ebook_category as $row) {
        $row->image = $this->getAdminImageUrl('category',$row->image);
        }

        $topbook =  Book::latest()->where(['in_deal'=>'Y','status'=>'Y'])->with(['authors'])->take(10)->get();

        foreach ($topbook as $row) {
            $row->image = $this->getAdminImageUrl('books',$row->image);
        }

        $cart_count = Cart::where(['user_id'=>$user->id])->count();

        $content = array(
            'bookbanner'=>$bookbanner,
            'hard_copy_category'=>$hard_copy_category,
            'ebook_category'=>$ebook_category,
            'topbook'=>$topbook,
            'cart_count'=>$cart_count
        );
        return response()->json([

            'result' => 'success',

            'message' => '',

            'content' =>$content

        ],200);

    }

    public function getCategoryWiseBooks(Request $request)
    {
        $validator =  Validator::make($request->all(), [

            'token' => 'required',
            'category_id' =>'required',
            'type'=>'required',

        ]);

        $content = array();

        $user = null;

        if ($validator->fails()) {

            return response()->json([

                'result' => 'failure',

                'message' => json_encode($validator->errors()),

                'content' =>$content

            ],400);

        }

        $user = JWTAuth::parseToken()->authenticate();

        if (empty($user)){

            return response()->json([

                'result' => 'failure',

                'message' => '',

                'content' =>$content

            ],401);

        }
        $type = request('type');
        $category_id = request('category_id');
        $content = Book::where(['category'=>$category_id])->where(['type'=>$type,'in_deal'=>'Y','status'=>'Y'])->with(['authors'])->paginate(10);
        foreach ($content as $row) {

            $row->cart_count = (int)Cart::where(['user_id'=>$user->id,'product_id'=>$row->id])->sum('qty');
            $row->image = $this->getAdminImageUrl('books',$row->image);
        }
        
        
         return response()->json([

            'result' => 'success',

            'message' => '',

            'content' =>$content

        ],200);
        

    }

    public function getBookDetails(Request $request)
    {
        $validator =  Validator::make($request->all(), [

            'token' => 'required',
            "book_id" =>'required',

        ]);

        $content = array();

        $user = null;

        if ($validator->fails()) {

            return response()->json([

                'result' => 'failure',

                'message' => json_encode($validator->errors()),

                'content' =>$content

            ],400);

        }

        $user = JWTAuth::parseToken()->authenticate();

        if (empty($user)){

            return response()->json([

                'result' => 'failure',

                'message' => '',

                'content' =>$content

            ],401);

        }

        $book_id = request('book_id');
        $content = Book::latest()->where(['id'=>$book_id,'in_deal'=>'Y','status'=>'Y'])->with(['authors','categories','publishers'])->first();
       
        $content->image = $this->getAdminImageUrl('books',$content->image);
        
         return response()->json([

            'result' => 'success',

            'message' => '',

            'content' =>$content

        ],200);
    }

    public function getCartCount(Request $request)
    {
        $validator =  Validator::make($request->all(), [

            'token' => 'required',

        ]);

        $content = array();

        $user = null;

        if ($validator->fails()) {

            return response()->json([

                'result' => 'failure',

                'message' => json_encode($validator->errors()),

                'content' =>$content

            ],400);

        }

        $user = JWTAuth::parseToken()->authenticate();

        if (empty($user)){

            return response()->json([

                'result' => 'failure',

                'message' => '',

                'content' =>$content

            ],401);

        }

        $user_id = $user->id;

        $content = Cart::where(['user_id'=>$user_id])->count();

         return response()->json([

            'result' => 'success',

            'message' => '',

            'content' =>$content

        ],200);
    }

    public function addToCart(Request $request)
    {
        $validator =  Validator::make($request->all(), [

            'token' => 'required',
            'product_id' =>'required',
            'qty' =>'required',

        ]);
        $user = null;

        if ($validator->fails()) {

            return response()->json([

                'result' => 'failure',

                'message' => json_encode($validator->errors()),

            ],400);

        }

        $user = JWTAuth::parseToken()->authenticate();

        if (empty($user)){

            return response()->json([

                'result' => 'failure',

                'message' => '',

            ],401);

        }

        $user_id = $user->id;
        $product_id = request('product_id');
        $qty = request('qty');

        $book = Book::where(['id'=>$product_id])->first();

        Cart::updateOrCreate(
            [
                'user_id'=>$user_id,
                'product_id'=>$product_id,
            ],
            [
                'qty'=>$qty,
                'price'=>$book->sale_price,
                'net_price'=>$qty*$book->sale_price,
            ]
        );

         return response()->json([

            'result' => 'success',

            'message' => 'Cart Updated',

        ],200);

    }

    public function removeCart(Request $request)
    {
       $validator =  Validator::make($request->all(), [

            'token' => 'required',
            'product_id' =>'required',
        ]);
        $user = null;

        if ($validator->fails()) {

            return response()->json([

                'result' => 'failure',

                'message' => json_encode($validator->errors()),

            ],400);

        }

        $user = JWTAuth::parseToken()->authenticate();

        if (empty($user)){

            return response()->json([

                'result' => 'failure',

                'message' => '',

            ],401);

        }

        $user_id = $user->id;
        $product_id = request('product_id');

        Cart::where(['user_id'=>$user_id,'product_id'=>$product_id])->delete();

         return response()->json([

            'result' => 'success',

            'message' => 'Remove Product Successfully',

        ],200);

    }

    public function getCart(Request $request)
    {
        $validator =  Validator::make($request->all(), [

            'token' => 'required',
        ]);
        $user = null;
        $content = array();

        if ($validator->fails()) {

            return response()->json([

                'result' => 'failure',

                'message' => json_encode($validator->errors()),
                'content' => $content

            ],400);

        }

        $user = JWTAuth::parseToken()->authenticate();

        if (empty($user)){

            return response()->json([

                'result' => 'failure',

                'message' => '',
                'content' => $content

            ],401);

        }
        $user_id = $user->id;
        $content = Cart::join('books','carts.product_id','=','books.id')
            ->where(['carts.user_id'=>$user_id])
            ->select([
                "books.*",
                "carts.qty as cart_count",
            ])
            ->get();
        foreach ($content as $c){
            $c->image = $this->getAdminImageUrl('books',$c->image);
        }

        return response()->json([
            'result' => 'success',
            'message' => 'Remove Product Successfully',
            'content' => $content
        ],200);

    }

    public function addAddress(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'person_name' => 'required',
            'phone' => 'required',
            'address1' => 'required',
            'address2' => '',
            'city' => 'required',
            'state' => 'required',
            'pin_code' => 'required',
            'is_default' => 'required',
        ]);
        if ($validator->fails()) {

            return response()->json([
                'result' => 'failure',
                'message' => json_encode($validator->errors()),
            ],400);
        }
        $user = JWTAuth::parseToken()->authenticate();
        if (empty($user)){
            return response()->json([
                'result' => 'failure',
                'message' => '',
            ],401);
        }
        $user_id = $user->id;

        Address::create([
            "user_id"=>$user_id,
            "person_name"=>$request->input("person_name"),
            "phone"=>$request->input("phone"),
            "address1"=>$request->input("address1"),
            "address2"=>$request->input("address2"),
            "city"=>$request->input("city"),
            "state"=>$request->input("state"),
            "pin_code"=>$request->input("pin_code"),
            "is_default"=>$request->input("is_default"),
            "status"=>"Y",
        ]);
        return response()->json([
            'result' => 'success',
            'message' => '',
        ],200);

    }

    public function updateAddress(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'addressId' => 'required',
            'person_name' => 'required',
            'phone' => 'required',
            'address1' => 'required',
            'address2' => '',
            'city' => 'required',
            'state' => 'required',
            'pin_code' => 'required',
            'is_default' => 'required',
        ]);
        if ($validator->fails()) {

            return response()->json([
                'result' => 'failure',
                'message' => json_encode($validator->errors()),
            ],400);
        }
        $user = JWTAuth::parseToken()->authenticate();
        if (empty($user)){
            return response()->json([
                'result' => 'failure',
                'message' => '',
            ],401);
        }
        $user_id = $user->id;
        if($request->input("is_default") == "Y"){
            Address::where(["user_id"=>$user_id,])->update(["is_default"=>"N",]);
        }
        Address::find($request->input("addressId"))->update([
            "user_id"=>$user_id,
            "person_name"=>$request->input("person_name"),
            "phone"=>$request->input("phone"),
            "address1"=>$request->input("address1"),
            "address2"=>$request->input("address2"),
            "city"=>$request->input("city"),
            "state"=>$request->input("state"),
            "pin_code"=>$request->input("pin_code"),
            "is_default"=>$request->input("is_default"),
            "status"=>"Y",
        ]);

        return response()->json([
            'result' => 'success',
            'message' => '',
        ],200);
    }

    public function getAddress(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
        ]);
        $content = [];
        if ($validator->fails()) {

            return response()->json([
                'result' => 'failure',
                'message' => json_encode($validator->errors()),
                'content'=>$content
            ],400);
        }
        $user = JWTAuth::parseToken()->authenticate();
        if (empty($user)){
            return response()->json([
                'result' => 'failure',
                'message' => '',
                'content'=>$content
            ],401);
        }
        $user_id = $user->id;
        $content = Address::where(["user_id"=>$user_id])->get();
        return response()->json([
            'result' => 'success',
            'message' => '',
            'content'=>$content
        ],200);
    }

    public function getCoupons(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
        ]);
        $content = [];
        if ($validator->fails()) {

            return response()->json([
                'result' => 'failure',
                'message' => json_encode($validator->errors()),
                'content'=>$content
            ],400);
        }
        $user = JWTAuth::parseToken()->authenticate();
        if (empty($user)){
            return response()->json([
                'result' => 'failure',
                'message' => '',
                'content'=>$content
            ],401);
        }
        $user_id = $user->id;
        $content = Coupon::whereDate('start_date', '<=',date("Y-m-d"))
            ->whereDate('end_date', '>=',date("Y-m-d"))
            ->where('status','=',1)
            ->get();

        return response()->json([
            'result' => 'success',
            'message' => '',
            'content'=>$content
        ],200);
    }

    public function myOrders(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
        ]);
        $content = [];
        if ($validator->fails()) {

            return response()->json([
                'result' => 'failure',
                'message' => json_encode($validator->errors()),
                'content'=>$content
            ],400);
        }
        $user = JWTAuth::parseToken()->authenticate();
        if (empty($user)){
            return response()->json([
                'result' => 'failure',
                'message' => '',
                'content'=>$content
            ],401);
        }
        $user_id = $user->id;
        $content = Order::where('user_id','=',$user_id)
            ->get();
        foreach ($content as $c){
            $c->items = json_decode($c->items);
        }
        return response()->json([
            'result' => 'success',
            'message' => '',
            'content'=>$content
        ],200);
    }


}
