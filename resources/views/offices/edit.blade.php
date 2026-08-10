@extends('layouts.app')

@section('title', 'កែប្រែការិយាល័យ')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <x-layout.page-header
        title="ការិយាល័យ"
        icon="building-2">

        <x-slot:breadcrumb>

            <x-layout.breadcrumb>


                <x-layout.breadcrumb-item
                    title="ការិយាល័យ"
                    :url="route('departments.index')" />

                <i
                    data-lucide="chevron-right"
                    class="w-4 h-4">
                </i>

                <x-layout.breadcrumb-item
                    title="កែប្រែការិយាល័យ" />

            </x-layout.breadcrumb>

        </x-slot:breadcrumb>

    </x-layout.page-header>

    <x-layout.page-card
        title="ព័ត៌មានការិយាល័យ"
        description="សូមកែប្រែព័ត៌មានការិយាល័យឱ្យបានត្រឹមត្រូវ"
        icon="clipboard-list">

        @include('offices._form', [
            'office' => $office,
            'department' => $departments,
        ])

    </x-layout.page-card>

</div>

@endsection