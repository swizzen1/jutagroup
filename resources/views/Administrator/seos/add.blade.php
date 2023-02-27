@extends('layouts.admin')
@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>@lang('admin.routes.' . $routes_suffix), @lang('admin.add')</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
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
                                    @if($translate_column === 'meta_description')
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
                                                >{{ old('translates.'.$lang['prefix'].'.meta_description') }}</textarea>
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
                                                          id="meta_description_{{ $lang["prefix"] }}" 
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
                                    <input type="text" 
                                           name="{{ $main_column }}"
                                           value="{{ old($main_column) }}" 
                                           class="form-control col-md-7 col-xs-12 socials"
                                    >
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                    <div class="form-group">
                        <div class="col-md-7 col-sm-7 col-xs-12 col-md-offset-3">
                            <button type="submit" class="btn btn-success">@lang('admin.add')</button>
                            <a href="{{ route($routes_suffix) }}" class="btn btn-warning">
                                @lang('admin.cancel')
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- meta title ფანჯარა -->
<div id="mt-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">
                    @lang('admin.nice_day')
                    <i class="far fa-smile"></i>
                </h4>
            </div>
            <div class="modal-body">
                <p>
                    <img src="/Administrator/images/mt.jpg" style="width: 100%; height: 100%;">
                </p>
            </div>
        </div>
    </div>
</div>
<!-- meta title ფანჯარა -->
@endsection
