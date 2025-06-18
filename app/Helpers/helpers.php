<?php
use Illuminate\Support\Facades\File;

if (!function_exists('uploadFile')) {
    /**
     * Upload a file to a specified folder and return the file path.
     *
     * @param \Illuminate\Http\UploadedFile $file The file to upload.
     * @param string $folder The folder where the file will be stored.
     * @param string|null $name Optional custom file name (without extension).
     * @return string The relative path of the uploaded file.
     */
    function uploadFile($file, $folder, $name = null)
    {
        $path = public_path($folder);

        // Ensure the directory exists
        if (!File::exists($path)) {
            File::makeDirectory($path, 0775, true, true);
        }

        // Generate a unique file name if not provided
        $filename = $name
            ? $name . '.' . $file->getClientOriginalExtension()
            : time() . '_' . $file->getClientOriginalName();

        // Move the file to the desired folder
        $file->move($path, $filename);

        // Return the relative path
        return $folder . '/' . $filename;
    }
}

if (!function_exists('can')) {

    function can($key)
    {
        $group_id = auth()->user()->group_id;
        $permissions = \App\Models\RollHas::where('roll_id', $group_id)
            ->join('permissions', 'roll_has.permission_id', '=', 'permissions.id')
            ->select('permissions.key')
            ->get()
            ->pluck('key')
            ->toArray();
        if (in_array($key, $permissions)) {
            return true;
        }
        return false;
    }
}
if (!function_exists('who')) {
    function who($key)
    {
        $group_id = auth()->user()->group_id;
        $roll=\App\Models\RoleAndPermission::where('id', $group_id)
            ->first();
        if(!empty($roll) && $roll->key==$key){
            return true;
        }else{
            return false;
        }
    }
}
if (!function_exists('get_notification')) {

    function get_notification()
    {
        $group_id = auth()->user()->group_id;
        $roll=\App\Models\RoleAndPermission::where('id', $group_id)
            ->first();
        if(!empty($roll) ){
            if ($roll->key == 'admin') {
                return [];
            }elseif ($roll->key == 'districtadmin') {
                return \App\Models\Student::where('status', 'Waiting for District Admin Approval')->where('notified', 'Pending')->orderBy('id', 'desc')->get();
            }elseif ($roll->key == 'chairmen') {
                return \App\Models\Student::where('status', 'Waiting for Chairman Approval')->where('notified', 'Pending')->orderBy('id', 'desc')->get();
            }else{
                return [];
            }
        }else{
            return [];
        }
        
    }
}


if (!function_exists('send_sms_new')) {

    function send_sms_new($to, $message)
    {
        
        $curl = curl_init();
        $newto = '880' . $to;
        echo $newto;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'Http://103.53.84.15:8746/sendtext',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{ 
                "apikey": "075b59787c645957", 
                "secretkey": "c48c5a54", 
                "callerID": "LDTAX", 
                "toUser": "' . $newto . '", 
                "messageContent": "' . $message . '"
                }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        return $response;
    }
}



