@extends('layouts.vertical', ['demo' => 'creative', 'title' => __('SLA Distance Groups')])
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ __('SLA Distance Groups') }}</h4>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-sm-12 text-sm-right">
            <a href="{{ route('distance-sla-groups.create') }}" class="btn btn-info waves-effect waves-light">
                <i class="mdi mdi-plus-circle mr-1"></i> {{ __('Create New Group') }}
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Rules') }}</th>
                                    <th>{{ __('Default') }}</th>
                                    <th>{{ __('Active') }}</th>
                                    <th class="text-right">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($groups as $group)
                                <tr class="{{ $group->is_default ? 'table-success' : '' }}">
                                    <td>{{ $group->name }}</td>
                                    <td>{{ $group->rules_count }}</td>
                                    <td>
                                        @if($group->is_default)
                                            <span class="badge badge-success">{{ __('Default') }}</span>
                                        @else
                                            <span class="badge badge-light text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($group->is_active)
                                            <span class="badge badge-primary">{{ __('Yes') }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __('No') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('distance-sla-groups.edit', $group) }}" class="btn btn-sm btn-soft-primary">{{ __('Edit') }}</a>
                                        @if(!$group->is_default)
                                            <form action="{{ route('distance-sla-groups.set-default', $group) }}" method="post" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-soft-success">{{ __('Set Default') }}</button>
                                            </form>
                                        @endif
                                        @if(!$group->is_default)
                                            <form action="{{ route('distance-sla-groups.destroy', $group) }}" method="post" class="d-inline" onsubmit="return confirm('{{ __('Delete this group?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-soft-danger">{{ __('Delete') }}</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">{{ __('No groups yet.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">
                        {{ $groups->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
