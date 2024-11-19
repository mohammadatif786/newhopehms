@extends('layouts.layout')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3>Uploaded Files for {{ $user->name }}</h3>
            </div>
            <div class="card-body">
                @if ($attachments->isEmpty())
                    <p>No files have been uploaded yet.</p>
                @else
                    <ul class="list-group">
                        @foreach ($attachments as $attachment)
                            <li class="list-group-item">
                                <a href="{{ asset('storage/attachments/' . $attachment->file_path) }}" target="_blank">
                                    {{ $attachment->file_path }}
                                </a>
                                <span class="badge badge-info">{{ strtoupper($attachment->file_type) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection
