@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard Overview')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white shadow-md p-4 rounded-lg">
            <h2 class="text-lg font-semibold">Users</h2>
            <p>Manage users here.</p>
        </div>
        <div class="bg-white shadow-md p-4 rounded-lg">
            <h2 class="text-lg font-semibold">Settings</h2>
            <p>Configure system settings.</p>
        </div>
        <div class="bg-white shadow-md p-4 rounded-lg">
            <h2 class="text-lg font-semibold">Reports</h2>
            <p>View reports and analytics.</p>
        </div>
    </div>
@endsection
