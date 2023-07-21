@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4>User Management</h4>
            </div>
            <div class="card-body">
                <a @can('create-user')
                href="{{ route('user.create') }}"
                @endcan
                    class="btn btn-success mb-3 disabled @if (!auth()->user()->can('create-book')) disabled @endif"
                    onclick="javascript::void()">CREATE</a>
                <x-alert />
                {{-- TODO: isi table dinamis dari database --}}
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            <tr>
                                <th scope="row">{{ $users->firstItem() + $key }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->roles }}</td>
                                <td>
                                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning me-3">EDIT</a>
                                    <div>
                                        <form method="POST" action="{{ route('user.delete', $user->id) }}">
                                            <x-delete />
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
