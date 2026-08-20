@extends('layouts.error')

@section('title', '404')

@section('content')

<x-error-card

    code="404"

    title="រកមិនឃើញទំព័រ"

    description="ទំព័រដែលអ្នកកំពុងស្វែងរកមិនមាននៅក្នុងប្រព័ន្ធទេ។"

    button="ត្រឡប់ទៅផ្ទាំងគ្រប់គ្រង"

    :url="auth()->check()
            ? route('dashboard')
            : route('login')" />

@endsection