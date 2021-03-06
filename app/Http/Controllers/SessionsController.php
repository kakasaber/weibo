<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class SessionsController extends Controller
{
    //
    public function __construct(){
      $this->middleware('guest',['only'=>['create']]);
    }
    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {

        $credentias = $this->validate($request,[
          'email' => 'required|email|max:255',
          'password' => 'required'
        ]);
        if(Auth::attempt($credentias, $request->has('remember'))){
            //登陆成功后的操作
            if(Auth::user()->activated){
              session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
              return redirect()->route('users.show', [Auth::user()]);
            }else{
              Auth::logout();
              session()->flash('warning','你的帐号没激活，请检查邮箱中的注册邮件进行激活');
              return redirect('/');
            }
          //  session()->flash('success','欢迎回来');

            // $fallback =  redirect()->route('users.show',[Auth::user()]);
            // return redirect()->intended($fallback);


        }else{
            //登陆失败后的操作
            session()->flash('danger','很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }

        return;
    }

    public function destroy(){
      Auth::logout();
      session()->flash('success','您已成功退出');
      return redirect('login');
    }
}
