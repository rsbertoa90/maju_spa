<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Mail\Cotizacion;
use App\Mail\Aviso;
use App\Order;
use App\Product;
use App\Category;
use App\OrderProduct;
use Mail;
use PDF;
use Carbon\Carbon;
use View;
use App\Jobs\GeneratePricesList;
use App\Jobs\GenerateCatalogo;
use Queue;
use App\ProductImage;
class PDFController extends Controller
{
    //

   

    public function pricesList()
    {
        
        $categories = Category::orderBy('name')->get();
       
        $today = Carbon::now()->format('d/m/Y');

         $html = View::make('pdf.ListaDePrecios',compact('categories','today'))->render();
        
         $pdf = PDF::loadHTML($html);

        return $pdf->download('MAJU-lista-de-precios.pdf');


    }
    
    public function dispatchPricesListJob()
    {


        Queue::push(new GeneratePricesList());

      return redirect('/home');
    }

     public function dispatchCatalogoJob()
    {
        Queue::push(new GenerateCatalogo());

        return redirect('/home');
    }

    public function testCatalogo(){
        $images = ProductImage::all();
        foreach ($images as $img) {
            $img->base64 = $img->tobase();
            $img->save();
        }


        $path = public_path().'/MAJU-catalogo.pdf';
        $today = Carbon::now()->format('d/m/Y');
        $categories = Category::orderBy('name')->get();

        $html = View::make('pdf.Catalogo3',compact('categories','today'))->render();

        return $html;
       /*  PDF::loadHTML($html)->save($path); */
    }



    public function catalogo()
    {
        set_time_limit(300);

       $categories = Category::orderBy('name')->get();
        
        $today = Carbon::now()->format('d/m/Y');
        
       /*  return view('pdf.Catalogo',compact('categories','today')); */
        
        $html = View::make('pdf.Catalogo',compact('categories','today'))->render();
        
        $pdf = PDF::loadHTML($html);

        return $pdf->download('catalogo.pdf');

    }
}
