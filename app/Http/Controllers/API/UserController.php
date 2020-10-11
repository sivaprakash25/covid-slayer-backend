<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Matches;
use Hash;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use DB;

class UserController extends Controller
{
    public function login(Request $request){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $userDetails = User::where('email', request('email'))->first();
            return response()->json(['success' => 'Logged in successfully','userDetails' => $userDetails], 200); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        }
    }

    public function register(Request $request){
        $name     = $request->name;
        $email    = $request->email;
        $password = $request->password;
        $avatar   = $request->avatar;
        $user     = User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make($password),
                        'avatar' => $avatar
                    ]);
        if($user){
            return response()->json(['success' => 'Registered successfully'], 200);
        } else {
            return response()->json(['error'=>'There is some issue with registration. Please contact site administrator'], 401); 
        }
    }

    public function matchLog(Request $request) {
        $user_id = $request->user_id;
        $log_path = $request->log_path;
        $message = $request->message;
        if(!is_array($message)){
           $message = array($message); 
        }

        if(empty($log_path)) {
            $log_path = $user_id.'_'.date('YmdHis').'.log';
        }
        foreach($message as $msg) {
            if(!is_array($msg)){
                $msg = array($msg); 
             }
            $matchLog = new Logger('match');
            $matchLog->pushHandler(
                (new StreamHandler(
                    storage_path('logs/'.$log_path), 
                    Logger::INFO
                ))->setFormatter(new LineFormatter(null, null, true, true))
            );
            $matchLog->info('matchLog', $msg);
        }

        return response()->json(['success' => 'inserted successfully', 'log_path' => $log_path], 200);
    }

    public function saveMatch(Request $request) {
        $user_id = $request->user_id;
        $result    = $request->result;
        $log_path = $request->log_path;
        try{
            $match = new Matches;
            $match->user_id = $request->user_id;
            $match->result = $request->result;
            $match->log_path = $request->log_path;
            $match->save();
            return response()->json(['success' => 'Record added succesfully'], 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>'Oops!! something went wrong'], 401);
        }
    }

    function matchReport($user_id) {
        try{
            $records = Matches::where('user_id', trim($user_id))->orderBy('id', 'DESC')->get();
            $query = DB::getQueryLog();
            return response()->json(['success' => 'Records fetched succesfully', 'records' => $records], 200);
        }
        catch(Exception $e){
            return response()->json(['error'=>'Oops!! something went wrong'], 401);
        }
    }
    
}
