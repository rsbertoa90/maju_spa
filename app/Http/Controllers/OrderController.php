<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\OrderProduct;
use App\Product;


use App\Mail\Cotizacion;
use App\Mail\Aviso;
use Mail;
use PDF;
use Carbon\Carbon;
use View;

class OrderController extends Controller
{
    public function get()
    {
        return Order::with('orderProducts.product.category')->get();
    }

    public function userOrder(Request $request)
    {
            $request->validate([
            'email'=>'required|email',
            'list'=>'required'
            ]);
             
      $products = json_decode($request->list);
      $phone = $request->phone;
      $message = $request->message;
      $email = $request->email;
      $total = $request->total;
            
      $order = Order::create([
          'email'=>$email,
          'phone'=>$phone,
          'message'=>$request->message,
          'name'=>$request->name,
          'source'=>'user'
      ]);

      foreach ($products as $p)
      {
         
        if ($p->units >= $p->pck_units){
            $price = $p->pck_price;
        }else {
            $price = $p->price;
        }
        OrderProduct::create([
              'product_id' => $p->id,
              'order_id'=>$order->id,
              'units'=>$p->units,
              'price'=>$price,
              ]);
      }

      $order = Order::find($order->id);
       
       Mail::to($order->email)
            ->send(new Cotizacion($order));

       MailController::mailAdmin(new Aviso());
            
    }

    public function adminOrder(Request $request)
    {
              $request->validate([
            'list'=>'required'
            ]);
             
       
      $order = Order::create([
          'email'=>$request->email,
          'phone'=>$request->phone,
          'message'=>$request->message,
          'name'=>$request->name,
          'seller' => $request->seller,
          'source'=>'admin'
      ]);

      $products = json_decode($request->list);

      foreach ($products as $p)
      {  
        if ($p->units >= $p->pck_units){
            $price = $p->pck_price;
        }else {
            $price = $p->price;
        }
        OrderProduct::create([
              'product_id' => $p->id,
              'order_id'=>$order->id,
              'units'=>$p->units,
              'price'=>$price,
                 
              ]);
      }

      $order = Order::find($order->id);
       
      $date  = Carbon::now()->format('Y-m-d');  
      $today  = Carbon::now()->format('d-m-Y H:i');  
      
      $html = View::make('pdf.Cotizacion',compact('order','today'))->render();
       
      $pdf = PDF::loadHTML($html);
          
      return $pdf->stream("{$date}-Cotizacion.pdf");
            
    }

    

   
}
