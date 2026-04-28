@extends('layouts.vertical', ['demo' => 'creative', 'title' => __('Create SLA Distance Group')])
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ __('Create SLA Distance Group') }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('distance-sla-groups.store') }}">
                        @csrf
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
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required maxlength="255">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                                    @if($errors->any()){{ old('is_active') ? 'checked' : '' }}@else checked @endif>
                                <label class="custom-control-label" for="is_active">{{ __('Active') }}</label>
                            </div>
                        </div>
                        @include('backend.distance-sla-groups.partials.default-group-option', [
                            'defaultChecked' => $errors->any() ? (bool) old('is_default') : false,
                            'showCurrentDefaultBadge' => false,
                            'groupIsDefault' => false,
                        ])
                        <hr>
                        <h5 class="mb-3">{{ __('Distance rules') }}</h5>
                        @include('backend.distance-sla-groups.partials.rules-table', ['rules' => null, 'generatorMode' => true])
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
    var OPEN_ENDED_LABEL = '{{ __('No upper limit') }}';
    var OPEN_ENDED_HINT  = '{{ __('Last band — open-ended') }}';
    var PLACEHOLDER_MSG  = '{{ __('Set the generator parameters above to create distance bands automatically.') }}';
    var MIN_LABEL        = '{{ __('min') }}';

    // Keep the hidden name-bearing inputs in sync with the visible generator inputs.
    function syncHidden() {
        $('#gen-initial-hidden').val($('#gen-initial').val());
        $('#gen-step-hidden').val($('#gen-step').val());
        $('#gen-threshold-hidden').val($('#gen-threshold').val());
    }

    // Round to 2 decimal places to avoid floating-point drift.
    function r2(n) { return Math.round(n * 100) / 100; }

    /**
     * Returns an array of { from, to } objects.
     * The last element always has to === null (open-ended band).
     */
    function generateRules(initial, step, threshold) {
        var rows = [];
        var from = parseFloat(initial);
        step      = parseFloat(step);
        threshold = parseFloat(threshold);

        if (isNaN(from) || isNaN(step) || isNaN(threshold) || step <= 0 || threshold <= 0) {
            return rows;
        }

        var safety = 0;
        while (safety++ < 1000) {
            var to = r2(from + step);
            if (to > threshold) {
                rows.push({ from: from, to: null });
                break;
            }
            rows.push({ from: from, to: to });
            from = to;
        }
        return rows;
    }

    /** Snapshot the current time values indexed by row position. */
    function collectTimes() {
        var times = [];
        $('#rules-tbody tr.rule-row').each(function() {
            times.push({
                tw:  $(this).find('input[name*="time_with_rider"]').val(),
                twr: $(this).find('input[name*="time_without_rider"]').val()
            });
        });
        return times;
    }

    /** Build the HTML for one generated row. */
    function buildRow(idx, from, to, tw, twr) {
        var isLast     = (to === null);
        var toDisplay  = isLast ? ('<em class="text-muted">' + OPEN_ENDED_LABEL + '</em>') : to;
        var toValue    = isLast ? '' : to;
        var openHint   = isLast ? ('<small class="form-text text-muted">' + OPEN_ENDED_HINT + '</small>') : '';
        var twVal      = (tw  !== null && tw  !== '') ? tw  : '';
        var twrVal     = (twr !== null && twr !== '') ? twr : '';

        return '<tr class="rule-row' + (isLast ? ' rule-row-open-ended' : '') + '">' +
            '<td class="align-middle">' +
                '<span class="rule-distance-display">' + from + '</span>' +
                '<input type="hidden" name="rules[' + idx + '][distance_from]" value="' + from + '">' +
            '</td>' +
            '<td class="align-middle">' +
                '<span class="rule-distance-display">' + toDisplay + '</span>' +
                openHint +
                '<input type="hidden" name="rules[' + idx + '][distance_to]" value="' + toValue + '">' +
            '</td>' +
            '<td>' +
                '<input type="number" class="form-control" ' +
                    'name="rules[' + idx + '][time_with_rider]" ' +
                    'value="' + twVal + '" required min="1" placeholder="' + MIN_LABEL + '">' +
            '</td>' +
            '<td>' +
                '<input type="number" class="form-control" ' +
                    'name="rules[' + idx + '][time_without_rider]" ' +
                    'value="' + twrVal + '" required min="1" placeholder="' + MIN_LABEL + '">' +
            '</td>' +
        '</tr>';
    }

    /** Rebuild the rules table from the current generator inputs. */
    function rebuildTable() {
        var initial   = $('#gen-initial').val();
        var step      = $('#gen-step').val();
        var threshold = $('#gen-threshold').val();
        syncHidden();

        if (initial === '' || step === '' || threshold === '') {
            $('#rules-tbody').html(
                '<tr id="gen-placeholder-row">' +
                '<td colspan="4" class="text-center text-muted small py-3">' + PLACEHOLDER_MSG + '</td>' +
                '</tr>'
            );
            return;
        }

        // Snapshot times BEFORE clearing the tbody.
        var existingTimes = collectTimes();
        var rows = generateRules(initial, step, threshold);

        if (rows.length === 0) {
            $('#rules-tbody').empty();
            return;
        }

        var html = '';
        for (var i = 0; i < rows.length; i++) {
            var t = existingTimes[i] || {};
            html += buildRow(i, rows[i].from, rows[i].to, t.tw || null, t.twr || null);
        }
        $('#rules-tbody').html(html);
    }

    // React to changes in the generator inputs.
    $('#gen-initial, #gen-step, #gen-threshold').on('input', function() {
        rebuildTable();
    });

    // On page load: auto-trigger when all three params are already set
    // (happens after a failed form submission restores old values).
    if ($('#gen-initial').val() !== '' && $('#gen-step').val() !== '' && $('#gen-threshold').val() !== '') {
        rebuildTable();
    }

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
