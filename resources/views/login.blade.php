@extends('layout.master')
@section('body', 'bg-gray-300')
@section('content')
    <h1 class="text-3xl text-center text-sky-900 mt-20 bg-gray-100 w-fit m-auto p-2 rounded-lg">Social Verse</h1>
    <div class="w-1/2 m-auto text-center bg-gray-100 rounded-lg py-5 mt-5">
        <form action="{{route('login')}}" method="post">
            @csrf
            <input type="text" value="" name="name" placeholder="Username" class=" outline-0 text-xl p-2 rounded-lg w-1/2 my-3 active:outline-1 active:outline-sky-100">
            <input type="password" value="" name="password" placeholder="Password" class=" outline-0 text-xl p-2 rounded-lg w-1/2 my-3 active:outline-sky-200 active:border-sm active:outline-1 active:outline-sky-100">
            <input type="submit" value="Login" class=" outline-0 text-xl p-2 rounded-lg w-1/2 cursor-pointer bg-gray-300 my-3 hover:bg-gray-400">
            <div>
                <a href="" class=" text-gray-600 hover:text-black">Create an account</a>
            </div>

        </form>
    </div>

    <div class="w-1/2 m-auto text-center bg-gray-100 rounded-full py-2 mt-5 cursor-pointer hover:bg-gray-200">
        <i class="fa-brands fa-google mr-3"></i><span>Log in with Google</span>
    </div>
@endsection


