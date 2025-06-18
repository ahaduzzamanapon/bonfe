<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Flash;
use Response;
use DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $users */
        $users = User::select('users.*', 'roles.name as role', 'designations.desi_name as designation','districts.name_en as district')
            ->leftjoin('roles', 'users.group_id', '=', 'roles.id')
            ->leftjoin('districts', 'users.district_id', '=', 'districts.id')
            ->leftjoin('designations', 'users.designation_id', '=', 'designations.id');
        if(!can('chairman') && can('district_admin')) {
            $users = $users->where('users.district_id', auth()->user()->district_id);
        }
        $users = $users->get();
        return view('users.index')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        return view('users.create');
    }


    public function store(Request $request)
    {
        $input = $request->all();

        if(isset($input['multiple_district'])){
            $multiple_district=$input['multiple_district'];
            unset($input['multiple_district']);
        }else{
            $multiple_district=[];
        }
       

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $folder = 'images/user';
            $customName = 'user-'.time();
            $input['image'] = uploadFile($file, $folder, $customName);
        }else{
            $input['image'] = 'no-image.png';
        }

        if ($request->hasFile('signature')) {
            $file = $request->file('signature');
            $folder = 'images/signature';
            $customName = 'signature-'.time();
            $input['signature'] = uploadFile($file, $folder, $customName);
        }else{
            $input['signature'] = 'no-image.png';
        }


        if ($request->has('password')) {
            $input['password'] = bcrypt($request->password);
        }else{
            $input['password'] = bcrypt('12345678');
        }



        /** @var User $users */
        $users = User::create($input);
        $id=$users->id;

         foreach ($multiple_district as $key => $value) {
            DB::table('multiple_district')->insert([
                'user_id' => $id,
                'district_id' => $value,
            ]);
        }


        Flash::success('User saved successfully.');

        return redirect(route('users.index'));
    }


    public function show($id)
    {
        /** @var User $users */
        $users = User::find($id);

        if (empty($users)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.show')->with('users', $users);
    }


    public function edit($id)
    {
        /** @var User $users */
        $users = User::find($id);
        //

        if (empty($users)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.edit')->with('users', $users);
    }


    public function update($id, Request $request)
    {
        /** @var User $users */
        $users = User::find($id);

        if (empty($users)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $input = $request->all();

          if(isset($input['multiple_district'])){
            $multiple_district=$input['multiple_district'];
            unset($input['multiple_district']);
        }else{
            $multiple_district=[];
        }

        DB::table('multiple_district')->where('user_id', $id)->delete();
        foreach ($multiple_district as $key => $value) {
            DB::table('multiple_district')->insert([
                'user_id' => $id,
                'district_id' => $value,
            ]);
        }







        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $folder = 'images/user';
            $customName = 'user-'.time();
            $input['image'] = uploadFile($file, $folder, $customName);
        }else{
            unset($input['image']);
        }


        if ($request->hasFile('signature')) {
            $file = $request->file('signature');
            $folder = 'images/signature';
            $customName = 'signature-'.time();
            $input['signature'] = uploadFile($file, $folder, $customName);
        }else{
            unset($input['signature']);
        }



        if ($request->has('password') && !empty($request->password)) {
            $input['password'] = bcrypt($request->password);
        }else{
            unset($input['password']);
        }
        $users->fill($input);
        $users->save();
        Flash::success('User updated successfully.');
        return redirect(route('users.index'));
    }


    public function destroy($id)
    {
        /** @var User $users */
        $users = User::find($id);
        if (empty($users)) {
            Flash::error('User not found');
            return redirect(route('users.index'));
        }
        $users->delete();
        Flash::success('User deleted successfully.');
        return redirect(route('users.index'));
    }
}
