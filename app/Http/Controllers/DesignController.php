<?php

namespace App\Http\Controllers;

use Aimeos\Shop\Facades\Product;
use Illuminate\Http\Request;
use Aimeos\Shop\Facades\Shop;
use App\Models\EditedProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class DesignController extends Controller
{
    public function design($id)
    {
       
        foreach( config('shop.page.design') as $name )
        {
            $params['aiheader'][$name] = Shop::get($name)->header();
            $params['aibody'][$name] = Shop::get($name)->body();
        }

        $int = (int)$id; 
        $product = DB::table('mshop_product')->where('id', $int)->first();
        $mediaProperty = DB::table('mshop_product_list')
        ->where('parentid', $product->id)
        ->where('domain', 'media')
        ->first();
    

        if ($mediaProperty) {
            $mediaItem = DB::table('mshop_media')
                ->where('id', $mediaProperty->refid)
                ->first();
        
            if ($mediaItem) {
                $imageInfo = array(
                    'label' => $mediaItem->label, 
                    'link' => $mediaItem->link
                );
                return View::make('design')->with(['params' => $params, 'imageInfo' => $imageInfo, 'mediaItem' => $mediaItem ]);
            }

            return redirect()->back()->with('errors', 'There is an issue opening Designlab.');
        }
        

        return redirect()->back()->with('errors', 'Something went wrong.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id); // Retrieve the product based on the provided ID

        return view('edit-product', compact('product'));
    }

    public function designPost(Request $request)
    {
        try 
        {
            $data = $request->all();
            $val = Validator::make($data,[
                'label' => ['required'],
                'refId' => ['required'],
                'image' => ['required'],
            ]);

            $image_decode = str_replace('data:image/png;base64,', '', $data['image']);
            $image_decode = base64_decode($image_decode, true);
            
        
            $filename = "edited-".uniqid()."-".$data['label'];
            

            if(!$val->fails())
            {
                // Save the image to the desired folder
                $filePath = Storage::disk('public')->put($filename, $image_decode);
                if ($filePath !== false) {

                    //DB logic
                    $insert = EditedProduct::create([
                        'user_id' => Auth::id(),
                        'ref_id' => $data['refId'],
                        'file_name' => 'storage/'.$filename, 
                    ]);

                    return response()->json([
                        'success' => 'Your file has been edited, head to the cart to complete your order.'
                    ], 200);
                }    

                return response()->json([
                    'error' => 'There is something wrong with the edited image, please try again later.'
                ], 400);
            }

        } 
        catch (\Exception $e) 
        {
            return response()->json([
                'error' => 'Something went wrong.'
            ], 500);
        }
    }
}
