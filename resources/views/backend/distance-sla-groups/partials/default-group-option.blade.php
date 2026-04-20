{{--
    $defaultChecked (bool): whether "default" checkbox is checked
    $showCurrentDefaultBadge (bool): show "Current default" badge (edit + group is default)
--}}
<div class="sla-default-option card border shadow-none mb-4">
    <div class="card-header bg-light border-bottom py-2 d-flex align-items-center flex-wrap">
        <i class="mdi mdi-star-outline text-primary mr-2 h4 mb-0"></i>
        <span class="font-weight-bold mb-0">{{ __('Default SLA group') }}</span>
        @if(!empty($showCurrentDefaultBadge))
            <span class="badge badge-success ml-2">{{ __('Current default') }}</span>
        @endif
    </div>
    <div class="card-body py-3">
        <div class="custom-control custom-checkbox">
            <input type="checkbox"
                   class="custom-control-input"
                   id="is_default"
                   name="is_default"
                   value="1"
                   data-initial-default="{{ !empty($groupIsDefault) ? 1 : 0 }}"
                   @if(!empty($defaultChecked)) checked @endif>
            <label class="custom-control-label font-weight-bold" for="is_default">
                {{ __('Use this group as the system default') }}
            </label>
        </div>
        <p class="text-muted small mb-0 mt-2 mb-2" id="sla-default-static-hint">
            {{ __('Only one default is allowed. If you turn this on, the previous default is cleared automatically when you save.') }}
        </p>
        <div class="alert alert-warning border-0 mb-0 py-2 px-3 small sla-default-confirm-banner"
             id="sla-default-confirm-banner"
             role="alert"
             style="display: none;">
            <div class="d-flex align-items-start">
                <i class="mdi mdi-alert-outline mr-2 mt-1"></i>
                <div>
                    <strong>{{ __('Default will change') }}</strong>
                    <span class="d-block mt-1">{{ __('After save, this group becomes the default and every other group will no longer be default.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
