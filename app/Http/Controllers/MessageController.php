<?php

namespace App\Http\Controllers;

use App\Message;
use App\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function userList(){
        if (!\Request::ajax()) {
            return abort(404);
        }
        $users = User::where('id','!=',auth()->user()->id)->latest()->get();
        return response()->json($users,200);

    }

    //get userMessage
    public function userMessage($id =null){
        if (!\Request::ajax()) {
            return abort(404);
        }

        $user = User::findOrFail($id); //get selected user
        $messages = $this->messageByUserId($id);
//    $messages = Message::where(function($query) use ($id){
//             $query->where('from',auth()->user()->id);
//             $query->where('to',$id);
//             $query->where('type',0);
//         })->orWhere(function($query) use ($id){
//             $query->where('from',$id);
//             $query->where('to',auth()->user()->id);
//             $query->where('type',1);
//         })->with('user')->get();

        return response()->json([
            'messages' => $messages,
            'user' => $user,
        ],200);


    }

    //user message send
    public function sendUserMessage(Request $request){
        if (!\Request::ajax()) {
            return abort(404);
        }
        $messages = Message::create([
            'message'=> $request->message,
            'from'=> auth()->user()->id,
            'to'=> $request->user_id,
            'type'=> 0,
        ]);

        $messages = Message::create([
            'message'=> $request->message,
            'from'=> auth()->user()->id,
            'to'=> $request->user_id,
            'type'=>1,
        ]);

        return response()->json($messages,201);
    }

    // delete single message
    public function deleteSingleMsg($msgId){
        if (!\Request::ajax()) {
            return abort(404);
        }

        Message::findOrFail($msgId)->delete();
        return response()->json('deleted',200);
    }

    //delete all msg
    public function deleteAllMsg($userId=null){
        if (!\Request::ajax()) {
            return abort(404);
        }
        $messages = $this->messageByUserId($userId);
        foreach ($messages as $value) {
            Message::findOrFail($value->id)->delete();
        }
        return response()->json('all deleted',200);
      
    }

   // use this funtion access for deleteallMsg function and userMessage funcion 
    public function messageByUserId($id){
        $messages = Message::where(function($query) use ($id){
            $query->where('from',auth()->user()->id);
            $query->where('to',$id);
            $query->where('type',0);
        })->orWhere(function($query) use ($id){
            $query->where('from',$id);
            $query->where('to',auth()->user()->id);
            $query->where('type',1);
        })->with('user')->get();

        return $messages;
    }

}
