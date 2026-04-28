@extends('layouts.vertical', ['demo' => 'creative', 'title' => __('Edit SLA Distance Group')])
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ __('Edit SLA Distance Group') }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('distance-sla-groups.update', $group) }}">
                        @csrf
                        @method('PUT')
                        @if ($errors->any())
                            <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
                                <div class="d-flex align-items-start">
                                    <i class="mdi mdi-alert-circle-outline text-danger mr-3 h3 mb-0"></i>
                                    <div>
                                        <h5 class="alert-heading mb-1">{{ __('We could not save your changes') }}</h5>
                                        <p class="mb-0 small">{{ __('Review the highlighted fields and the note below, then try again.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <label>{{ __('Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $group->name) }}" required maxlength="255" min="5">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                                    @if($errors->any()){{ old('is_active') ? 'checked' : '' }}@else{{ $group->is_active ? 'checked' : '' }}@endif>
                                <label class="custom-control-label" for="is_active">{{ __('Active') }}</label>
                            </div>
                        </div>
                        @include('backend.distance-sla-groups.partials.default-group-option', [
                            'defaultChecked' => $errors->any() ? (bool) old('is_default') : (bool) $group->is_default,
                            'showCurrentDefaultBadge' => (bool) $group->is_default,
                            'groupIsDefault' => (bool) $group->is_default,
                        ])
                        <hr>
                        <h5 class="mb-3">{{ __('Distance rules') }}</h5>
                        @include('backend.distance-sla-groups.partials.rules-table', ['rules' => $group->rules])
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                            <a href="{{ route('distance-sla-groups.index') }}" class="btn btn-light">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
(function() {
    var OPEN_ENDED_PLACEHOLDER = '{{ __('No upper limit') }}';

    function applyOpenEndedLastRow() {
        var $rows = $('#rules-tbody tr.rule-row');
        if ($rows.length === 0) return;
        $rows.each(function(i) {
            var $row = $(this);
            var $to = $row.find('input.rule-distance-to');
            var $hint = $row.find('.rule-open-ended-hint');
            var isLast = (i === $rows.length - 1);
            if (isLast) {
                var current = $to.val();
                if (current !== '' && current !== null && typeof current !== 'undefined') {
                    $to.attr('data-saved-to', current);
                }
                $to.val('');
                $to.prop('readonly', true);
                $to.removeAttr('required');
                $to.attr('placeholder', OPEN_ENDED_PLACEHOLDER);
                $to.addClass('bg-light');
                $row.addClass('rule-row-open-ended');
                $hint.show();
            } else {
                var saved = $to.attr('data-saved-to');
                if (saved && $to.val() === '') {
                    $to.val(saved);
                }
                $to.removeAttr('data-saved-to');
                $to.prop('readonly', false);
                $to.attr('required', 'required');
                $to.attr('placeholder', '');
                $to.removeClass('bg-light');
                $row.removeClass('rule-row-open-ended');
                $hint.hide();
            }
        });
    }

    function reindexRules() {
        $('#rules-tbody tr.rule-row').each(function(i) {
            $(this).find('input').each(function() {
                var name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/rules\[\d+\]/, 'rules[' + i + ']'));
                }
            });
        });
        applyOpenEndedLastRow();
    }

    $('#add-rule-row').on('click', function() {
        var $first = $('#rules-tbody tr.rule-row').first();
        var $clone = $first.clone();
        $clone.find('input').val('');
        $clone.find('input.rule-distance-to').removeAttr('data-saved-to');
        $clone.removeClass('rule-row-open-ended');
        $('#rules-tbody').append($clone);
        reindexRules();
    });
    $(document).on('click', '.remove-rule', function() {
        if ($('#rules-tbody tr.rule-row').length <= 1) {
            if (typeof $.NotificationApp !== 'undefined') {
                $.NotificationApp.send('{{ __('Rules') }}', '{{ __('Keep at least one distance rule.') }}', 'top-right', '#bf441d', 'error');
            } else {
                alert('{{ __('Keep at least one distance rule.') }}');
            }
            return;
        }
        $(this).closest('tr').remove();
        reindexRules();
    });

    applyOpenEndedLastRow();

    (function slaDefaultBanner() {
        var $cb = $('#is_default');
        var $banner = $('#sla-default-confirm-banner');
        if (!$cb.length || !$banner.length) return;
        var initialDefault = String($cb.data('initial-default')) === '1';
        var touched = false;
        $cb.on('change', function() { touched = true; sync(); });
        function sync() {
            if (!$cb.prop('checked')) {
                $banner.stop(true, true).slideUp(180);
                return;
            }
            if (initialDefault && !touched) {
                $banner.stop(true, true).slideUp(180);
                return;
            }
            $banner.stop(true, true).slideDown(180);
        }
        sync();
    })();
})();
</script>
@endsection
