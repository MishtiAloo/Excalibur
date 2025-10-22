@extends('layouts.layout')

@section('title','Contact')

@section('content')
<div style="max-width:800px;margin:40px auto;padding:20px;background:rgba(0,0,0,0.05);border-radius:8px;">
    <h1>Contact Us</h1>
    <h4 style="color: #fff">If you have questions or need help, contact the Excalibur team using the form below.</h4>
    <br>
    <form method="POST" action="#">
        @csrf
        <div style="margin-bottom:8px;">
            <label for="name">Name</label>
            <input type="text" id="name" style="width:100%;" />
        </div>
        <div style="margin-bottom:8px;">
            <label for="email">Email</label>
            <input type="email" id="email" style="width:100%;" />
        </div>
        <div style="margin-bottom:8px;">
            <label for="message">Message</label>
            <textarea id="message" style="width:100%;" rows="6"></textarea>
        </div>
        <button type="submit" style="background:#3b82f6;color:#fff;padding:8px 12px;border:none;border-radius:6px;">Send</button>
    </form>
</div>
@endsection
