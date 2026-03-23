@extends('admin.layout')
@section('title', 'Category Setup')
@section('header', 'Category Setup')

@section('content')
    <h1 class="page-title">Categories</h1>
    <p class="page-subtitle">Add or remove product categories</p>

    {{-- ── Add Category Form ────────────────────────── --}}
    <div class="card">
        <div class="card-title">New Category</div>
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="flex gap-8 items-center">
                <div style="flex: 1;">
                    <input type="text" name="name" class="form-control" placeholder="Category name" value="{{ old('name') }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Category</button>
            </div>
        </form>
    </div>

    {{-- ── Categories Table ─────────────────────────── --}}
    <div class="table-wrap">
        <div class="table-header">
            <h2>All Categories ({{ $categories->count() }})</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Created</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td style="font-weight: 500;">{{ $category->name }}</td>
                        <td class="text-muted">{{ $category->slug }}</td>
                        <td>{{ $category->created_at ? $category->created_at->format('d M Y') : '—' }}</td>
                        <td class="text-right">
                            <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty-state">No categories yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
