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

                // Capture the image URL
                $imageUrl = $data['image_url'];

                // Capture result
                $resultLabel = $data['results'];
                $labels = array_column($resultLabel, 'label');
                $resultStr = implode(", ", $labels);

                // give response success
                return back()->with('success', 'Image uploaded and processed successfully')
                ->with('image_url', $imageUrl)
                ->with('resultbreed', $resultStr);

            } else {
                // give response failed
                return back()->with('error', 'Failed to send image to Flask server');
            }
        } else {
            // this is logic send data to api weight detector
            
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
    
            $image = $request->file('image');
    
            // send image to server flask
            $response = $this->sendImageToFlaskServer2($image);
    
            if ($response->successful()) {
                $data = $response->json();

                // Capture the image URL
                $imageUrl = $data['image_url'];

                // Capture result
                // Capture result weights and class names
                $resultLabel = $data['results'];

                // Create a string of labels and weights for display
                $resultStr = [];
                foreach ($resultLabel as $result) {
                    $resultStr[] = $result['label'] . ': ' . number_format($result['weight'], 2) . ' kg';
                }
                $resultStr = implode(", ", $resultStr);

                $dataWeight = $data['total_weight'];
                $formattedNumber = number_format($dataWeight, 2);
                $totalWeight = $formattedNumber . ' KG';

                // give response success
                return back()->with('success', 'Image uploaded and processed successfully')
                ->with('image_url2', $imageUrl)
                ->with('resultweight', $resultStr)
                ->with('totalweight', $totalWeight);

            } else {
                // give response failed
                return back()->with('error', 'Failed to send image to Flask server');
            }
        }
        
    }

    private function sendImageToFlaskServer($image)
    {
        // cattle breed Flask api
        $url = 'http://192.168.1.3:5000/predict';  // URL Flask Server

        $imagePath = $image->getPathname();
        $imageName = $image->getClientOriginalName();

        return Http::attach(
            'image', file_get_contents($imagePath), $imageName
        )->post($url);
    }

    private function sendImageToFlaskServer2($image)
    {
        // cattle weight Flask api
        $url = 'http://192.168.1.3:5000/predict';  // URL Flask Server

        $imagePath = $image->getPathname();
        $imageName = $image->getClientOriginalName();

        return Http::attach(
            'image', file_get_contents($imagePath), $imageName
        )->post($url);
    }

}
