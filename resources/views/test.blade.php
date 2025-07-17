@extends('layouts.app')
@section('title', 'Test Debugbar')

@section('content')
<div class="container">
    <h1>Danh sách User và các bài viết</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên User</th>
                <th>Email</th>
                <th>Số lượng bài viết</th>
                <th>Tiêu đề các bài viết</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->posts->count() }}</td>
                    <td>
                        <ul>
                            @foreach ($user->posts as $post)
                                <li>{{ $post->title }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
