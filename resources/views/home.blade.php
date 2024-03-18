@extends('layouts.app')
@section('content')
<home :user="{{ Session::get('user') }}" :token=`{{ Session::get('token') }}` />
@endsection