@php 
    $page = array_key_exists('page', request()->route()->parameters) 
            ? request()->route()->parameters['page'] : 1;
@endphp
@extends('layouts.admin')
@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>@lang('admin.routes.' . $routes_suffix), @lang('admin.edit')</h2>
            <p class="pull-right"> 
                @for($s=1; $s <= 5; $s++)
                    <i class="fa fa-star" style="{{ $item->rate < $s ? 'opacity: 0.2': '' }}"></i>
                @endfor
            </p>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            @if(Session::has('success'))
            <div class="alert alert-success alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <strong>@lang('admin.success')</strong>
            </div>
            @endif
            @if(Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <strong>@lang('admin.error')</strong>
            </div>
            @endif
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
                      action="{{ route('Update'.$routes_suffix, $item->id) }}"
                >
                    @csrf
                    <input type="hidden" 
                           name="last_edited_lang" 
                           value="{{ Session::has('last_edited_lang') ? Session::get('last_edited_lang') : $configuration->admin_lang }}" 
                           id="lat-edited-lang-inp"
                    >
                    <input type="hidden" name="page" value="{{ $page }}">
                    <div id="myTabContent" class="tab-content">
                        @forelse($Localization as $key => $lang)
                            @php
                                $item_info = $model::getItemInfo($item->id , $lang['prefix'], $status_on=false);
                            @endphp
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
                                                    @lang('admin.' . $translate_column) 
                                                    @if(in_array($translate_column,$required_columns))
                                                        <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <div class="col-md-7 col-sm-7 col-xs-12">
                                                    <textarea name="translates[{{ $lang['prefix'] }}][short_description]"  
                                                              class="form-control col-md-7 col-xs-12" 
                                                              rows="5"
                                                    >{{ $item_info->short_description }}</textarea>
                                                </div>
                                            </div>
                                        @elseif($translate_column === 'description')
                                            <div class="form-group not-need {{ $errors->has('translates.'.$lang['prefix'].'.description') ? 'bad' : '' }}">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                                    @lang('admin.' . $translate_column) 
                                                    @if(in_array($translate_column,$required_columns))
                                                        <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <div class="col-md-7 col-sm-7 col-xs-12">
                                                    <textarea name="translates[{{ $lang['prefix'] }}][description]"  
                                                              class="form-control col-md-7 col-xs-12" 
                                                              id="short_description_{{ $lang["prefix"] }}" 
                                                    >{{ $item_info->description }}</textarea>
                                                </div>
                                            </div>
                                        @elseif($translate_column === 'meta_description')
                                            <div class="form-group not-need {{ $errors->has('translates.'.$lang['prefix'].'.meta_description') ? 'bad' : '' }}">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                                    @lang('admin.' . $translate_column) 
                                                    @if(in_array($translate_column,$required_columns))
                                                        <span class="required">*</span>
                                                    @endif
                                                </label>
                                                <div class="col-md-7 col-sm-7 col-xs-12">
                                                    <textarea name="translates[{{ $lang['prefix'] }}][meta_description]"  
                                                              class="form-control col-md-7 col-xs-12" 
                                                              rows="5"
                                                    >{{ $item_info->meta_description }}</textarea>
                                                </div>
                                            </div>
                                        @else
                                            <div class="form-group {{ $errors->has('translates.'.$lang['prefix'].'.'.$translate_column) ? 'bad' : '' }}">
                                                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                                    @lang('admin.' . $translate_column) 
                                                    @if(in_array($translate_column,$required_columns))
                                                        <span class="required">*</span>
                                                    @endif
                                                    @if($translate_column == 'meta_title')
                                                        <i class="fas fa-question-circle"
                                                           data-toggle="modal" 
                                                           data-target="#mt-modal"
                                                           style="cursor: pointer"
                                                        ></i>
                                                    @endif
                                                </label>
                                                <div class="col-md-7 col-sm-7 col-xs-12">
                                                    <input type="text" 
                                                           name="translates[{{ $lang['prefix'] }}][{{ $translate_column }}]" 
                                                           value="{{ $item_info->$translate_column }}"  
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
                    </div>
                    @forelse($main_columns as $main_column)
                        <div class="form-group {{ $errors->has($main_column) ? 'bad' : '' }}">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">
                                @lang('admin.' . $main_column) 
                                @if(in_array($main_column,$required_columns))
                                    <span class="required">*</span>
                                @endif
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                                <input type="text" 
                                       name="{{ $main_column }}" 
                                       value="{{ $item->$main_column }}"  
                                       class="form-control col-md-7 col-xs-12 socials"
                                >
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
                                    <option value="{{ $brand->id }}" {{ $item->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->title }}</option>
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
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('admin.image')</label>
                        <div class="col-md-7 col-sm-7 col-xs-12 text-center">
                            <input type="file" name="image" class="form-control col-md-7 col-xs-12">
                            <div class="img-or-no">
                                @if($item->image)
                                    <br /><br />
                                    <img src="{{ $item->image }}" width="40%">
                                @else
                                    <div class="alert alert-warning" style="margin-top: 40px;">@lang('admin.not_uploaded')</div>
                                @endif
                            </div>
                        </div>
                        @if($item->image)
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <a href="" 
                                   class="btn btn-danger remove-file" 
                                   data-id="{{ $item->id }}"
                                   data-table="{{ $main_table }}"
                                >
                                    X
                                </a>
                            </div>
                        @endif
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
                    <div class="form-group {{ $errors->has('file') ? 'bad' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">
                            @lang('admin.photogallery') 
                        </label>
                        <div class="col-md-7 col-sm-7 col-xs-12" style="padding: 0px; {{ $errors->has('files.*') ? 'border: 1px solid red;' : '' }}">
                            <div class="upload-wr">
                                <div class="upload" style="margin: 0;">
                                    <div class="upload__head">
                                        <label for="fileUp" class="upload__lab-lf" >
                                            <span>@lang('admin.choose_files')</span>
                                        </label>
                                        <label for="fileUp" class="upload__lab-rt">
                                            @lang('admin.choose')
                                        </label>
                                        <input type="file" name="files[]" 
                                               style="display: none;" 
                                               multiple 
                                               id="fileUp" 
                                               class="upload__input" 
                                        >
                                    </div>
                                    <ul class="upload__file-ls" id="upload__file-ls">

                                    </ul>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0; margin-top: 10px;">
                                    @forelse($item->images as $image)
                                        <div class="col-md-4">
                                            <div class="thumbnail">
                                                <div class="image view view-first">
                                                    <img style="width: 100%; display: block;" src="{{ $image->image }}">
                                                    <div class="mask">
                                                        <p>Your Text</p>
                                                        <div class="tools tools-bottom">
                                                            <a href="#"><i class="fa fa-link"></i></a>
                                                            <a href="#"><i class="fa fa-pencil"></i></a>
                                                            <a href="#" 
                                                               class="remove-image"  
                                                               data-id="{{ $image->id }}"
                                                               data-gallery-table="{{ $image_gallery_table }}"
                                                            >
                                                                <i class="fa fa-times"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    @endif
                    <div class="form-group" style="padding: 10px 0;">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">
                            @lang('admin.color_images')
                        </label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                @forelse(json_decode($item->color_images) as $k => $image)
                                    <div class="col-md-4 item-remove">
                                        <div class="thumbnail" style="position: relative;">
                                            <div class="image view view-first">
                                                <img style="width: 100%; height: 100%;display: block; object-fit: cover;" src="{{ $image[0] }}">
                                            </div>
                                            <button class="btn btn-danger col-lg-12 DeleteImageModel" 
                                                    data-id="{{ $item->id }}"
                                                    data-index="{{ $k }}"
                                            >
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                                <div class="col-md-4">
                                    <div class="thumbnail add-video-thumbnail align-middle">
                                        <a href="#" id="add-color-img">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <div class="col-md-7 col-sm-7 col-xs-12" id="color-img-wr">
                            <div class="color-img-div" id="color-list"> 

                            </div>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">
                            @lang('admin.new')
                        </label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <input type="checkbox" class="flat" name="new"  {{ $item->new ? 'checked' : ''  }}/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">
                            @lang('admin.available')
                        </label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <input type="checkbox" class="flat" name="available"  {{ $item->available ? 'checked' : ''  }}/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">
                            @lang('admin.publish')
                        </label>
                        <div class="col-md-7 col-sm-7 col-xs-12">
                            <input type="checkbox" class="flat" name="status"  {{ $item->status ? 'checked' : ''  }}/>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <input type="hidden" name="stay" id="stay-input">
                    <div class="form-group">
                        <div class="col-md-7 col-sm-7 col-xs-12 col-md-offset-3">
                            <button type="button" class="btn btn-success save-btn"  data-stay="1">
                                @lang('admin.save')
                            </button>
                            <button type="button" class="btn btn-success save-btn"  data-stay="0">
                                @lang('admin.save_and_close')
                            </button>
                            <a href="{{ url('/admin/products/product?page=' . $page) }}" class="btn btn-warning">
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
    $("#category-select").val({{ $item->category_id }});
    
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
    
    $('.DeleteImageModel').click(function(event){
        
        event.preventDefault();
        
        var imageIndex =  $(this).data('index');
        var productID =  $(this).data('id');
        var _This = $(this);
        
        bootbox.confirm({
            message: areYouSure, // ცვლადი აღწერილია layouts/admin.blade.php-ში
            buttons: {
                confirm: {
                    label: yes, // ცვლადი აღწერილია layouts/admin.blade.php-ში
                    className: 'btn-success' // ცვლადი აღწერილია layouts/admin.blade.php-ში
                },
                cancel: {
                    label: no,
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if(result) 
                {
                    $.ajax({
                    url : '{{ route('RemoveColorImage'.$routes_suffix) }}',
                    type : 'post',
                    dataType : 'json',
                    data : { '_token' : '{{ csrf_token() }}' , 'product_id'  : productID, 'image_index' : imageIndex}
                    })
                    .done(function(data)
                    {
                        if(data.status == 1) 
                        {
                            $(_This).parents('.item-remove').remove();

                            new PNotify({
                                text: '{{ trans("admin.success") }}',
                                type: 'success',
                                styling: 'bootstrap3'
                            });
                        }

                        else if(data.status == 2)
                        {
                            new PNotify({
                                text: '{{ trans("admin.error") }}',
                                type: 'error',
                                styling: 'bootstrap3'
                            });
                        }

                        else if(data.status == 5)
                        {
                            new PNotify({
                                text: '{{ trans("admin.no_permission") }}',
                                type: 'error',
                                styling: 'bootstrap3'
                            });
                        }
                    });
                }
            }
        });
        
    });
       
</script>
@endpush
