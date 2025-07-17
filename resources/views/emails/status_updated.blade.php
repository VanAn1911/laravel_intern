<p>Xin chào {{ $post->user->name }},</p>

<p>Trạng thái bài viết <strong>{{ $post->title }}</strong> đã được cập nhật thành: <strong>{{ $post->status->label() }}</strong></p>

<p>Trân trọng,<br>Admin</p>
