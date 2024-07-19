<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MenuController extends Controller
{
    function upload(Request $request) {  // upload to local website
        if ($request->hasFile('image')) {
            
            // image validate
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Sesuaikan dengan kebutuhan Anda
            ]);

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('pictures'), $imageName); // Simpan gambar di folder public/img

            // Return URL gambar yang baru saja diupload
            return response()->json(['status' => 'success', 'message' => 'upload success', 'data' => $imageName], 200);
        } else {
            // Jika tidak ada gambar yang diupload
            return response()->json(['status' => 'failed', 'message' => 'No image uploaded'], 400);
        }
    }

    public function store(Request $request) // send image to endpoint
    {
        if ($request->action == 'breed') {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
    
            $image = $request->file('image');
    
            // send image to server flask
            $response = $this->sendImageToFlaskServer($image);
    
            if ($response->successful()) {
                $data = $response->json();
    
                // save path image to database if needed
                // ...

                // give response success
                return back()->with('success', 'Image uploaded and received response: ' . json_encode($data));
            } else {
                // give response failed
                return back()->with('error', 'Failed to send image to Flask server');
            }
        } else {
            // this is logic send data to api weight detector
            return back()->with('success', 'Weight Detector');
        }
        
    }

    private function sendImageToFlaskServer($image)
    {
        // cattle breed Flask api
        $url = 'http://192.168.1.3:5000/requestget';  // URL Flask Server

        $imagePath = $image->getPathname();
        $imageName = $image->getClientOriginalName();

        return Http::attach(
            'image', file_get_contents($imagePath), $imageName
        )->post($url);
    }

}
