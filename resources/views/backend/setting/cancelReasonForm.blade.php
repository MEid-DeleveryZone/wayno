<div class="row">
    <div class="col-md-12">
        @if(isset($client_languages) && count($client_languages) > 0)
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('language_id', __('Select Language'),['class' => 'control-label']) !!}
                    <select class="form-control" id="cancel_reason_language_selector" name="language_id">
                       @foreach($client_languages as $client_language)
                        <option value="{{$client_language->langId}}" @if($client_language->is_primary == 1) selected @endif>{{$client_language->langName}}</option>
                       @endforeach
                    </select>
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="form-group" id="reasonInput">
                    @php
                        $reasonValue = '';
                        if(isset($cancelReason->id) && $cancelReason->id > 0) {
                            $reasonValue = ($cancelReason->primary && $cancelReason->primary->reason) ? $cancelReason->primary->reason : $cancelReason->name;
                        }
                    @endphp
                    {!! Form::label('reason', __('Reason'),['class' => 'control-label']) !!}
                    {!! Form::text('reason', $reasonValue, ['class' => 'form-control', 'id' => 'cancel_reason_field', 'placeholder'=>'Enter Reason', 'required' => 'required']) !!}
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group" id="statusInput">
                    {!! Form::label('status', __('Status'),['class' => 'control-label']) !!}
                    <select class="form-control" name="status" id="cancel_reason_status_field" required>
                        <option value="">Select Status</option>
                        <option value="1" @if(isset($cancelReason->id) && $cancelReason->status == 1) selected @endif>Active</option>
                        <option value="2" @if(isset($cancelReason->id) && $cancelReason->status == 2) selected @endif>Inactive</option>
                    </select>
                    <span class="invalid-feedback" role="alert">
                        <strong></strong>
                    </span>
                </div>
            </div>
            <input type="hidden" id="cancel_reason_id" url="{{ (isset($cancelReason->id) && $cancelReason->id > 0) ? route('cancelReason.createUpdate') : route('cancelReason.createUpdate') }}" data-cancel-reason-id="{{ isset($cancelReason->id) ? $cancelReason->id : '' }}">
            <input type="hidden" name="id" value="{{ isset($cancelReason->id) ? $cancelReason->id : '' }}">
        </div>
    </div>
</div>

