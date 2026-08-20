@extends('layouts.error')

@section('title', '404')

@section('content')

    <x-error-card code="403" title="មិនមានសិទ្ធិចូលប្រើ"
        description="អ្នកមិនមានសិទ្ធិចូលប្រើទំព័រនេះទេ។ សូមទាក់ទងអ្នកគ្រប់គ្រងប្រព័ន្ធ។" button="ត្រឡប់ទៅផ្ទាំងគ្រប់គ្រង"
        :url="auth()->check() ? route('dashboard') : route('login')" />

@endsection
