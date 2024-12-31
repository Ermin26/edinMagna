<?php

namespace App\Http\Controllers;
use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Location;



class AppController extends Controller{
    public function home(){
        $materials = Material::all();

        return view('home', compact('materials'));
    }

    public function addLocation(Request $request){
        try{
            $newLocation = $request->input('location');
            $errors = [];
            $success = [];
            foreach($newLocation as $loc){
                if($this->checkNewLocation($loc)){
                    $errors[] = $loc;
                }else{
                    $location = new Location();
                    $location->location = $loc;
                    $location->save();
                    $success[] = $loc;
                }
            }
            $locations = $success ? 'Location/s > '.implode(', ', $newLocation).' < added successfully.' : null;
            $locationExists = $errors ? 'Location/s > '.implode(' ', $errors).' < already exists.' : null;
            return redirect()->back()->with('success', $locations)
                                    ->with( 'error', $locationExists);
        }catch(ValidationException $e){
            $errors = $e->validator->errors()->all();
            return redirect()->back()->with('error', implode(', ', $errors));
        }
    }
    public function getAllMaterials(){
        $materials = Material::all();
        return view('addMaterial', compact('materials'));
    }

    public function getAllLocations(){
        $locations = Location::all();
        return view('newLocation', compact('locations'));
    }

    public function addMaterial(Request $request){

        try{

            $materials = $request->input('material');
            $locations = $request->input('location');
            $errors = [];
            $success = [];
            
            if(count($materials) > 1 && count($locations) == 1){
                if($this->checkNewLocation($locations[0])){
                    foreach($materials as $newMaterial){
                        $material = new Material();
                        $material->material = $newMaterial;
                        $material->location = $locations[0];
                        $success[] = ['material'=>$newMaterial, 'location'=>$locations[0]];
                        $material->save();
                    }
                }else{
                        $errors[] = $locations[0];
                    }
            }elseif(count($materials)== 1 && count($locations) > 1){
                foreach($locations as $location){
                    if($this->checkNewLocation($location)){
                        $material = new Material();
                        $material->material = $materials[0];
                        $material->location = $location;
                        $success[] = ['material'=>$materials[0], 'location'=>$location];
                        $material->save();
                    }else{
                        $errors[] = $location;
                    }
                }
            }else{
                foreach($locations as $location){
                    if($this->checkNewLocation($location)){
                        foreach($materials as $newMaterial){
                            $material = new Material();
                            $material->material = $newMaterial;
                            $material->location = $location;
                            $success[] = ['material'=>$newMaterial, 'location'=>$location];
                            $material->save();
                        }
                    }else{
                        $errors[] = $location;
                    }
                }
            }

            $successMsg = $success ? 'Material/s '.implode(',', array_map(function($entry){
                return $entry['material']. ' at '. $entry['location'];}, $success)) .' added successfully.' : null;
            $errorsMsg = $errors ? 'Materials are not added because location/s '.implode(',', $errors).' not exists.' : null;

            return redirect()->back()->with('success', $successMsg)
                                        ->with('error', $errorsMsg);

        }catch(ValidationException $e){
            $errors = $e->validator->errors()->all();
            return redirect()->back()->with('error', implode(', ', $errors));
        }
    }

    private function checkNewLocation($newLocation){
        return Location::where('location', $newLocation)->exists();
    }
}