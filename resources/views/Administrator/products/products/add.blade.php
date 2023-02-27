@extends('layouts.admin')
@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>@lang('admin.routes.' . $routes_suffix), @lang('admin.add')</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            @if(Session::has('cat_level_err'))
            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <strong>{{ Session::get('cat_level_err') }}</strong>
            </div>
            @endif
            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                    @forelse($Localization as $key => $lang)
                    <li role="presentation" class="">
                        <a href="#{{ $lang['prefix'] }}" 
                           id="home-tab" 
                           class="lang-switcher" 
                           role="tab" 
                           data-toggle="tab" 
                           aria-expanded="true"
                           data-lang="{{ $lang['prefix'] }}"
                        >
                            {{ $lang['name'] }}
                        </a>
                    </li>
                    @empty
                    @endforelse
                </ul>
                <form id="news-form"  
                      class="form-horizontal form-label-left" 
                      method="post" enctype="multipart/form-data" 
                      action="{{ route('Store'.$routes_suffix) }}"
                >
                    @csrf
                    <input type="hidden" 
                           name="last_edited_lang" 
                           value="{{ Session::has('last_edited_lang') ? Session::get('last_edited_lang') : $configuration->admin_lang }}" 
                           id="lat-edited-lang-inp"
                    >
                    <div id="myTabContent" class="tab-content">
                        @forelse($Localization as $key => $lang)
                         <div role="tabpanel" 
                             class="tab-pane fadein" 
                             id="{{ $lang['prefix'] }}" 
                             aria-labelledby="home-tab"
                        >
                            @isset($translate_columns)
                                @forelse($translate_columns as $translate_column)
                                    @if($translate_column === 'short_description')
                                        <div class="form-group not-need {{ $errors->has('translates.'.$lang['prefix'].'.short_description') ? 'bad' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                                @lang('admin.' . $translate_column) <span class="required">*</span>
                                            </label>
                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                <textarea name="translates[{{ $lang['prefix'] }}][short_description]"  
                                                          class="form-control col-md-7 col-xs-12" 
                                                >{{ old('translates.'.$lang['prefix'].'.short_description') }}</textarea>
                                            </div>
                                        </div>
                                    @elseif($translate_column === 'description')
                                        <div class="form-group not-need">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                                @lang('admin.' . $translate_column) <span class="required">*</span>
                                            </label>
                                            <div class="col-md-7 col-sm-7 col-xs-12"
                                                 style="border: 1px solid {{ $errors->has('translates.'.$lang['prefix'].'.description') ? 'red' : 'white' }}"
                                            >
                                                <textarea name="translates[{{ $lang['prefix'] }}][description]"  
                                                          class="form-control col-md-7 col-xs-12" 
                                                          id="short_description_{{ $lang["prefix"] }}" 
                                                >{{ old('translates.'.$lang['prefix'].'.description') }}</textarea>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group {{ $errors->has('translates.'.$lang['prefix'].'.'.$translate_column) ? 'bad' : '' }}">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                                @lang('admin.' . $translate_column) 
                                                @if(in_array($translate_column,$required_columns))
                                                    <span class="required">*</span>
                                                @endif
                                            </label>
                                            <div class="col-md-7 col-sm-7 col-xs-12">
                                                <input type="text" 
                                                       name="translates[{{ $lang['prefix'] }}][{{ $translate_column }}]"  
                                                       value="{{ old('translates.'.$lang['prefix'].'.'.$translate_column) }}"
                                                       class="form-control col-md-7 col-xs-12"
                                                >
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                @endforelse
                            @endisset
                            <div class="ln_solid"></div>
                        </div>
                        @empty
                        @endforelse
                        @forelse($main_columns as $main_column)
                            <div class="form-group {{ $errors->has($main_column) ? 'bad' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                    @lang('admin.' . $main_column) 
                                    @if(in_array($main_column,$required_columns))
                                        <span class="required">*</span>
                                    @endif
                                </label>
                                <div class="col-md-7 col-sm-7 col-xs-12">
                                    <input type="text" name="{{ $main_column }}" value="{{ old($main_column) }}"  class="form-control col-md-7 col-xs-12 socials">
                                </div>
                            </div>
                        @empty
                        @endforelse
                        <div class="form-group not-need {{ $errors->has('brand_id') ? 'bad' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                @lang('admin.brand')
                                @if(in_array('brand_id',$required_columns))
                                    <span class="required">*</span>
                                @endif
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <select name="brand_id" class="form-control col-md-7 col-xs-12">
                                    <option value="0" >@lang('admin.brand')</option>
                                    @forelse($brands as $brand)
                                        <option value="{{ $brand->id }}" 
                                                {{ old('brand_id') == $brand->id ? 'selected' : '' }}
                                        >
                                            {{ $brand->title }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="form-group not-need">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                @lang('admin.parent_category')
                                @if(in_array('image',$required_columns))
                                    <span class="required">*</span>
                                @endif
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                @include('Administrator.categories.categories')
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group {{ $errors->has('image') ? 'bad' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                @lang('admin.image') 
                                @if(in_array('image',$required_columns))
                                    <span class="required">*</span>
                                @endif
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <input type="file" name="image" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                        @php 
                            if(Session::get('admin')->role == 2)
                            {
                                $actions = json_decode(DB::table('configurations')->first()->standard_admin_actions);                      
                            }
                            else if(Session::get('admin')->role == 3)
                            {
                                $actions = json_decode(DB::table('configurations')->first()->moderator_admin_actions);  
                            }
                        @endphp
                        @if(Session::get('admin')->role == 1 || in_array('RemoveImages', $actions))
                        <div class="ln_solid"></div>
                        <div class="form-group bad">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                 @lang('admin.photogallery') 
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12" style="{{ $errors->has('files.*') ? 'border: 1px solid red;' : '' }}">
                                <div class="upload-wr">
                                    <div class="upload">
                                        <div class="upload__head">
                                            <label for="fileUp" class="upload__lab-lf" >
                                                <span>@lang('admin.choose_files')</span>
                                            </label>
                                            <label for="fileUp" class="upload__lab-rt">
                                                @lang('admin.choose')
                                            </label>
                                            <input type="file" name="files[]" style="display: none;" multiple id="fileUp" class="upload__input" >
                                        </div>
                                        <ul class="upload__file-ls" id="upload__file-ls">

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                @lang('admin.color_images')
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12" id="color-img-wr">
                                <div class="color-img-div" id="color-list"> 
                                    <div class="color-img-div" 
                                         style="display: grid; grid-template-columns: 1fr 1fr; grid-gap: 0 5px; position: relative;  margin: 0 0 14px 0;"
                                    >
                                        <div>
                                            <input type="file" 
                                                   name="color_images[]" 
                                                   class="form-control col-md-9 col-xs-12"
                                            >
                                        </div>
                                        <div>
                                            <div id="color-picker-component" 
                                                 class="car-colors input-group colorpicker-component" 
                                                 style="margin: 0;" 
                                            >
                                                <input type="text" 
                                                       name="colors[]" 
                                                       value="#000" 
                                                       class="form-control"
                                                />
                                                <span class="input-group-addon"><i></i></span>
                                            </div>
                                        </div>
                                        <span class="fa fa-close  right delete-color-img" 
                                              style="position: absolute; bottom: 105%; right: -5px; cursor: pointer;" 
                                        ></span>
                                    </div>
                                </div>
                                <span class="btn btn-primary btn-md" 
                                      id="add-color-img" 
                                      style="margin: 0 0 0 0; width: 100%;"
                                > 
                                    <i class="fa fa-plus"></i> @lang('admin.color_images') 
                                </span>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"> 
                                @lang('admin.new')
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <input type="checkbox" class="flat" name="new" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"> 
                                @lang('admin.available')
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <input type="checkbox" class="flat" name="available" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"> 
                                @lang('admin.publish')
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <input type="checkbox" class="flat" name="status" />
                            </div>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-7 col-sm-7 col-xs-12 col-md-offset-3">
                            <button type="submit" class="btn btn-success">@lang('admin.add')</button>
                            <a href="{{ route($routes_suffix) }}" class="btn btn-success">
                                @lang('admin.cancel')
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="/Administrator/vendors/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<script>
    
    $('#category-select').attr('name','category_id');
    $('#category-select option:eq(0)').text("@lang('admin.category')");
    $('.parent-option').attr('disabled',true);
    
    $('.car-colors').colorpicker();
    
    $('#add-color-img').click(function (event) {
        
        event.preventDefault();

        $('#color-list').append(`<div class = "color-img-div" style = "display: grid; grid-template-columns: 1fr 1fr; grid-gap: 0 5px; position: relative; margin: 0 0 24px 0;">
                                    <div>
                                        <input type = "file" name = "color_images[]" class = "form-control col-md-7 col-xs-12">
                                    </div>
                                    <div>
                                        <div id = "color-picker-component" class = "car-colors input-group colorpicker-component" >
                                            <input type = "text" name = "colors[]" value = "#000" class = "form-control" />
                                            <span class = "input-group-addon" ><i></i></span>
                                        </div>
                                    </div>
                                    <a class="btn btn-danger btn-xs delete-color-img" style="border-radius: 50% !important; position: absolute; bottom: 105%; right: -5px;">X</a>
                                </div>`);

        $('.car-colors').colorpicker();
        
    });

    $('body').delegate('.delete-color-img', 'click', function (event) {
        $(this).parent().remove();
    });
    
</script>
@endpush                        
                            