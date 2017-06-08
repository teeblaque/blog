<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Post;
use Mail;

class PagesController extends Controller
{
    public function getIndex(){

        $brand = "Teez";
        $last ="world";
        $word  = $brand . " " . $last;

        $posts = Post::orderBy('created_at', 'desc')->limit(5)->get();

    	return view('pages.welcome')->withWord($word)->withPosts($posts);
    }

    public function getAbout(){

    	$first = 'Taiwo';
    	$last = 'Ajayi';

    	$full = $first . " " . $last;
    	$email = "ajayitaiwo47@outlook.com";

    	return view('pages.about')->with("fullname", $full)->withEmail($email);
    }

    public function getContact(){

        $first = 'teeblaque';

    	return view('pages.contact')->withFullname($first);
    }

    public function postContact(Request $request){

        $this->validate($request, ['email' =>'required|email',
                'subject' => 'min:3',
                'message' => 'min:10']);

        $data = array(
                'email' => $request->email,
                'subject' => $request->subject,
                'bodymessage' => $request->message

            );

        Mail::send('emails.contact', $data, function($message) use($data){
            $message->from($data['email']);
            $message->to('teeblaque-80cd7d@inbox.mailtrap.io');
            $message->subject($data['subject']);
        });

        Session::flash('success', 'Your email was sent successfully');

        return redirect()->url('/'); 
    }

}
