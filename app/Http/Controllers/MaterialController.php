<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;
use \Illuminate\Validation\ValidationException;
use App\Models\Material;
use App\Models\Location;
use PHPUnit\Util\Xml\ValidationResult;

class MaterialController extends Controller{

    function materials(){
        if(Auth::check()){
            return Material::all();
        }else{
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    function show($id){
        if(Auth::check()){
            return Material::find($id);
        }else{
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    function store(Request $request){
        if(!$this->checkRole()){
            try{
                $data = $request->data;
                $locationCount = 0;
                $materialCount = 0;
                $locations = [];
                $materials = [];
                $suppliers = [];
                foreach($data as $group){
                    foreach($group as $item){
                        if($item['label'] == 'Location'){
                            $locationCount ++;
                            $locations[]= $item['value'];
                        }else if($item['label'] == 'Material'){
                            $materialCount++;
                            $materials[] = $item['value'];
                        }else if($item['label'] == 'Supplier'){
                            $suppliers[] = $item['value'];
                        }
                    }
                }
                $materialsToInsert = [];
                $success = [];
                $errors = [];

                if(count($locations) > 1 && count($materials) == 1){
                    for($i = 0; $i < count($locations); $i++){
                        $location = $locations[$i];
                        if($this->checkNewLocation($location)){
                            $materialsToInsert[] = [
                                'material' => $materials[0],
                                'location' => $locations[$i],
                                'supplier' => $suppliers[0],
                                'added' => Auth::user()->name,
                            ];
                            $success[] = ['material'=>$materials[0], 'location'=>$locations[$i]];
                        }else{
                            $errors[] = $location;
                        }
                    }

                }else if(count($locations) == 1 && count($materials) > 1){
                    if($this->checkNewLocation($locations[0])){
                        for ($i = 0; $i < count($materials); $i++){
                            $materialsToInsert[] = [
                                'material' => $materials[$i],
                                'location' => $locations[0],
                                'supplier' => $suppliers[$i],
                                'added' => Auth::user()->name,
                            ];
                            $success[] = ['material'=>$materials[$i], 'location'=>$locations[0]];
                        }
                    }else{
                            $errors[] = $locations[0];
                    }
                }else{
                    $count = min(count($locations), count($materials), count($suppliers));
                    for($i = 0; $i < $count; $i++){
                        $location = $locations[$i];
                        if($this->checkNewLocation($location)){
                            $materialsToInsert[] = [
                                'material' => $materials[$i],
                                'location' => $locations[$i],
                                'supplier' => $suppliers[$i],
                                'added' => Auth::user()->name,
                            ];
                            $success[] = ['material'=>$materials[$i], 'location'=>$locations[$i]];
                        }else{
                            $errors[] = $location;
                        }
                    }
                }

                Material::insert($materialsToInsert);
                $successMsg = $success ? 'Material/s '.implode(',', array_map(function($entry){
                    return $entry['material']. ' at location '. $entry['location'];}, $success)) .' added successfully.' : null;
                $errorsMsg = $errors ? 'Materials are not added because location/s '.implode(',', $errors).' not exists.' : null;
                return response()->json([
                    'success' => $successMsg,
                    'errors' => $errorsMsg
                ], 200);
            }catch(ValidationException $e){
                $errors = $e->validator->errors()->all();
                return response()->json([
                    'message' => $errors,
                    'errors' => implode(',', $errors)
                ], 400);
            }
        }else{
            return response()->json(['message' => 'You are not authorized to add a new material.'], 403);
        }
    }

    public function update(Request $request, $id) {
        if(!$this->checkRole()){
            try{
                $request->validate([
                    'location' =>'required|string',
                    'material' =>'required|string',
                    'supplier' =>'required|string',
                ]);

                $material = Material::find($id);
                if($this->checkNewLocation($request->location)){
                    $material->material = $request->material;
                    $material->location = $request->location;
                    $material->supplier = $request->supplier;
                    $material->updated_by = Auth::user()->name;
                    $material->save();
                    return response()->json([
                        'message' => 'Material updated successfully',
                        'data' => $material
                    ], 200);
                }

            }catch(ValidationException $e){
                $errors = $e->validator->errors()->all();
                return response()->json([
                    'message' => $errors,
                    'errors' => implode(',', $errors)
                ], 400);
            }

            $material->update($request->all());
            return $material;
        }else{
            return response()->json(['message' => 'You are not authorized to update a material'], 403);
        }
    }

    public function delete($id) {
        if(!$this->checkRole()){
            try{
                Material::find($id)->delete();
                return response()->json([
                    'success' => 'Material deleted successfully'  ,
                ], 200);
            }catch(ValidationException $e){
                $errors = $e->validator->errors()->all();
                return response()->json([
                    'message' => $errors,
                    'errors' =>  implode(', ', $errors)], 400);
            }
        }else{
            return response()->json(['message' => 'You are not authorized to delete a material'], 403);
        }
    }

    public function logout(Request $request){
        if(Auth::check()){
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response()->json(['message' => 'Logged out'], 200);
        }else{
            return response()->json(['message' => "Error logging out"]);
        }
    }


    private function checkNewLocation($newLocation){
        return Location::where('location', $newLocation)->exists();
    }

    private function checkRole(){
        return Auth::user()->role == 'visitor';
    }

}