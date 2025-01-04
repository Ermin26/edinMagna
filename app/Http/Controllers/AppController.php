<?php

namespace App\Http\Controllers;
use \Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Location;
use App\Models\User;



class AppController extends Controller{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function userLogin(Request $request){
        try{
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string'
            ]);

            $user = User::where('name', $request->username)->first();
            if($user && Hash::check($request->password, $user->password)){
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->route('home');
            }else{
                return redirect()->back()->with('error', 'Invalid credentials.');
            }
        }catch(ValidationException $e){
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    public function logout(Request $request){
        if(Auth::check()){
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('success', 'Loged out.');
        }else{
            return redirect()->route('login')->with('error', 'You are not logged in.');
        }
    }
    public function home(){
        $materials = Material::all();

        return view('home', compact('materials'));
    }

    public function addUser(Request $request){
        if($this->checkRole()){
        try{
            $request->validate([
                'username' => 'required|string',
                'role' => 'required|string',
                'password' => 'required|string|min:5|max:255|confirmed'
            ]);
            User::create([
                'name' => $request->input('username'),
                'role' => $request->input('role'),
                'password' =>  Hash::make($request->input('password'))
            ]);
            return redirect()->back()->with('success', "User {$request->input('username')} added successfully");
        }catch(ValidationException $e){
            $error = $e->validator->errors()->all();
            $errors = implode('<br>', $error);
            return redirect()->back()->with('error', $errors);
        }
        }else{
            return redirect()->back()->with('error', 'You are not authorized to add any data. ');
        }
    }
    public function getAllUsers() {
        $users = User::all()->pluck('name')->toArray();
        return view('addUser', compact('users'));
    }
    public function addLocation(Request $request){
        if($this->checkRole()){
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
                    $location->added = Auth::user()->name;
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
        }else{
            return redirect()->back()->with('error', 'You are not authorized to add any data. ');
        }
    }
    public function getAllMaterials(){
        $materials = Material::all();
        $locations = Location::all();
        return view('addMaterial', compact('materials', 'locations'));
    }

    public function getAllLocations(){
        $locations = Location::all();
        return view('newLocation', compact('locations'));
    }

    public function addMaterial(Request $request){
        if($this->checkRole()){
            try{
                $materials = $request->input('material');
                $locations = $request->input('location');
                $suppliers = $request->input('supplier');
                $errors = [];
                $success = [];
                $materialsToInsert = [];

                if(count($materials) > 1 && count($locations) == 1){
                    if($this->checkNewLocation($locations[0])){
                        $count = count($materials);
                        for ($i = 0; $i < $count; $i++){
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
                }elseif(count($materials)== 1 && count($locations) > 1){
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
                            }else{
                                $errors[] = $location;
                            }
                    }
                }
                Material::insert($materialsToInsert);
                $successMsg = $success ? 'Material/s '.implode(',', array_map(function($entry){
                    return $entry['material']. ' at '. $entry['location'];}, $success)) .' added successfully.' : null;
                $errorsMsg = $errors ? 'Materials are not added because location/s '.implode(',', $errors).' not exists.' : null;

                return redirect()->back()->with('success', $successMsg)
                                            ->with('error', $errorsMsg);

            }catch(ValidationException $e){
                $errors = $e->validator->errors()->all();
                return redirect()->back()->with('error', implode(', ', $errors));
            }
        }else{
            return redirect()->back()->with('error', 'You are not authorized to add any data. ');
        }
    }

    public function editUser(){
        $users = User::all();
        return view('editUser', compact('users'));
    }

    public function updateUser(Request $request){
        if($this->checkRole()){
        try{
            $request->validate([
                'username' => 'string|required',
                'role' => 'string|required',
                'password' => 'string|nullable|min:5|confirmed'
            ]);
            $user = User::find($request->input('username'));
            if($user){
                $name = $request->input('name');
                $role = $request->input('role');
                $pass = $request->input('password') ? $request->input('password') : $user->password;

                if($user->name != 'Edin' || Auth::user()->name == $user->name){
                    $user->update([
                        'name'=> $request->input('name') ? $name : $user->name,
                        'role'=> $role,
                        'password'=> $pass,
                        'updated_by' => Auth::user()->name
                    ]);
                    return redirect()->back()->with('success', "User {$user->name} updated successfully");
                }else{
                    return redirect()->back()->with('error', 'You can not edit user Edin.');
                }
            }else{
                return redirect()->back()->with('error', 'User not found.');
            }
        }catch(ValidationException $e){
            $errors = $e->validator->errors()->all();
            return redirect()->back()->with('error', implode(', ', $errors));
        }
        }else{
            return redirect()->back()->with('error', 'You are not authorized to add any data. ');
        }
    }

    public function editMaterial(Request $request, $id){
        $material = Material::find($id);
        if($material){
            return view('editMaterial', compact('material'));
        }
    }

    public function updateMaterial(Request $request, $id){
        if($this->checkRole()){
        try{
            $request->validate([
                'material' => 'string|required',
                'location' => 'string|required',
                'supplier' => 'string|required',
            ]);
            $material = Material::find($id);
            if($this->checkNewLocation($request->input('location'))){
                $material->material = $request->input('material');
                $material->location = $request->input('location');
                $material->supplier = $request->input('supplier');
                $material->updated_by = Auth::user()->name;
                $material->save();
                return redirect()->back()->with('success', "Material '{$request->input('material')}' succesfully updated at '{$request->input('location')}'");
            }else{
                return redirect()->back()->with('error', 'Location not exists.');
            }
        }catch(ValidationException $e){
            $errors = $e->validator->errors()->all();
            return redirect()->back()->with('error', implode(', ', $errors));
        }
        }else{
            return redirect()->back()->with('error', 'You are not authorized to change any data. ');
        }
    }
    public function editLocation($id){
        $location = Location::find($id);
        if($location && $this->checkRole()){
            return view('editLocation', compact('location'));
        }else {
            return redirect()->back()->with('error', 'You are not authorized to edit locations.');
        }
    }

    public function updateLocation(Request $request, $id){
        if($this->checkRole()){
            $location = Location::find($id);
            $location->location = $request->input('location');
            $location->updated_by = Auth::user()->name;
            $location->save();
            return redirect()->back()->with('success', "Location '{$location->location}' sucssefily updated.");
        }else{
            return redirect()->back()->with('error', 'You are not authorized to add any data. ');
        }
    }

    public function editProfile(Request $request, $id){
        $user = User::find($id);
        if($user){
            return view('editProfile', compact('user'));
        }else{
            return redirect()->back()->with('error', 'User not found.');
        }
    }
    public function updateProfile(Request $request, $id){
        try{
            $user = User::find($id);
            $request->validate([
                'name' => 'string|',
                'password' => 'string|nullable|min:5|confirmed'
            ]);
            $password = $request->input('password') ? Hash::make($request->input('password')) : $user->password;
            $user->name = $request->input('name');
            $user->password = $password;
            $user->save();
            return redirect()->back()->with('success', 'Data updated successfully.');

        }catch(ValidationException $e){
            $errors = $e->validator->errors()->all();
            return redirect()->back()->with('error', implode(', ', $errors));
        }
    }

    private function checkNewLocation($newLocation){
        return Location::where('location', $newLocation)->exists();
    }

    private function checkRole(){
        return Auth::user()->role == 'admin' || Auth::user()->role == 'moderator';
    }
}