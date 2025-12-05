@extends('layouts.app')

@section('title', 'Kursus Ryan Komputer - Main Page')

@section('body-class', '')

@section('content')

@include('components.navbar')
@include('components.footer')
@include('components.styles')
@include('components.scripts')
@include('landing.index')
@endsection