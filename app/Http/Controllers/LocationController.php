<?php

namespace App\Http\Controllers;
use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Location;


class LocationController extends Controller{
    public function locations(){
        return Location::all();
    }

    public function existingLocations(){
        return response()->json(Location::pluck('location')->toArray());
    }

    public function newLocations(Request $request){
        if(!$this->checkRole()){
            try{
                $locations = $request->locations;
                $errors = [];
                $success = [];

                foreach($locations as $newLocation){
                    $locationValue = $newLocation['value'];
                    if($this->checkNewLocation($locationValue)){
                        $errors[]=$locationValue;
                    }else{
                        $loc = new Location();
                        $loc->location = $locationValue;
                        $loc->added = Auth::user()->name;
                        $loc->save();
                        $success[]=$locationValue;
                    }
                }
                $locationsSuccess = $success ? 'Location/s > '.implode(', ', $success).' < added successfully.' : null;
                $locationExists = $errors ? 'Location/s > '.implode(' ', $errors).' < already exists.' : null;
                return response()->json([
                    'errors' => $locationExists,
                    'success' => $locationsSuccess,
                    'user' => Auth::user()->role
                ], 200);
            }catch(ValidationException $e){
                $errors = $e->validator->errors()->all();
                return response()->json([
                    'message' => $errors,
                    'errors' =>  implode(', ', $errors)], 400);
            }
        }else{
            return response()->json(['message' => 'You are not authorized to add a new location.'], 403);
        }
    }
    public function location($id){
        return Location::find($id);
    }

    public function updateLocation(Request $request, $id){
        if(!$this->checkRole()){
            $request->validate([
                'location' =>'required|string'
            ]);

            $newLocation = $request->location;
            if($this->checkNewLocation($newLocation)){
                return response()->json([
                    'message' => 'Location already exists.'
                ], 400);
            }else{
                try{
                    $location = Location::find($id);
                    $location->location = $newLocation;
                    $location->updated_by = Auth::user()->name;
                    $location->save();
                    return response()->json([
                        'success' => 'Location updated successfully' ,
                        'newLocation' => $location
                    ], 200);
                }catch(ValidationException $e){
                    $errors = $e->validator->errors()->all();
                    return response()->json([
                        'message' => $errors,
                        'errors' =>  implode(', ', $errors)], 400);
                }
            }
        }else{
            return response()->json([
                'message' => 'You are not authorized to update a location.'
            ], 403);
        }
    }

    public function deleteLocation($id){
        if(!$this->checkRole()){
            try{
                Location::find($id)->delete();
                return response()->json([
                    'success' => 'Location deleted successfully'  ,
                ],200);
            }catch(ValidationException $e){
                $errors = $e->validator->errors()->all();
                return response()->json([
                    'message' => $errors,
                    'errors' =>  implode(', ', $errors)], 400);
            }
        }else{
            return response()->json(['message' => 'You are not authorized to delete a location'], 403);
        }
    }

    private function checkNewLocation($newLocation){
        return Location::where('location', $newLocation)->exists();
    }

    private function checkRole(){
        return Auth::user()->role == 'visitor';
    }
}