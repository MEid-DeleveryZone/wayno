<div class="row">
        <div class="col-xl-8">
            <div class="card-box home-options-list">
                <div class="row mb-2">
                    <div class="col-sm-8">
                        <h4 class="page-title mt-0">Home Page</h4>
                        <p class="sub-header">
                            Drag & drop to edit different sections.
                        </p>
                    </div>
                    <div class="col-sm-4 text-right">
                        <button class="btn btn-info waves-effect waves-light text-sm-right" id="save_home_page">Save</button>
                    </div>
                </div>

                <div class="custom-dd-empty dd" id="homepage_datatablex">
                    <ol class="dd-list p-0" id="homepage_ol">
                        @foreach($home_page_labels as $home_page_label)
                        <li class="dd-item dd3-item d-flex align-items-center" data-id="1" data-row-id="{{$home_page_label->id}}">
                            <a herf="#" class="dd-handle dd3-handle d-block mr-auto">
                                @if($home_page_label->slug == "vendors")
                                {{getNomenclatureName('Vendors', true)}}
                                @else
                                {{$home_page_label->title}}
                                @endif
                            </a>
                            <div class="language-inputs style-4">
                                <div class="row no-gutters flex-nowrap align-items-center my-2">
                                    @foreach($langs as $lang)
                                    @php
                                    $exist = 0;
                                    $value = '';
                                    @endphp
                                    <div class="col-3 pl-1">
                                        <input class="form-control" type="hidden" value="{{$home_page_label->id}}" name="home_labels[]">
                                        <input class="form-control" type="hidden" value="{{$lang->langId}}" name="languages[]">
                                        @foreach($home_page_label->translations as $translation)
                                        @if($translation->language_id == $lang->langId)
                                        @php
                                        $exist = 1;
                                        $value = $translation->title;
                                        @endphp
                                        @endif
                                        @endforeach
                                        <input class="form-control" value="{{$exist == 1 ? $value : '' }}" type="text" name="names[]" placeholder="{{ $lang->langName }}">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="mb-0 ml-3">
                                <input type="checkbox" {{$home_page_label->is_active == 1 ? 'checked' : ''}} id="{{$home_page_label->slug}}" data-plugin="switchery" name="{{$home_page_label->slug}}" class="chk_box2" data-color="#43bee1">
                            </div>
                           
                        </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>