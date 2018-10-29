<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Suplier;
class SuplierController extends Controller
{
    public function getAll()
    {
        return Suplier::with('products')->get();
    }

     public function save(Request $request)
    {
        $suplier = Suplier::create(['name' => $request->name]);

        return $suplier;
    }

     public function update(Request $request)
    {
        $suplier = Suplier::find($request->id);
        $field = $request->field;
        $suplier->$field = $request->value;
        $suplier->save();
    }

    public function destroy($id)
    {
        Suplier::destroy($id);
    }
}
