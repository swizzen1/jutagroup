@extends('layouts.admin')
@section('content')
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <form method="post" action="" id="multi-form">
                @csrf
                <div class="x_title">
                    <h2>@lang('admin.routes.' . $routes_suffix)</h2>
                    
                    <a class="btn btn-success btn-sm pull-right" href="{{ route('Add'.$routes_suffix) }}"> 
                        <i class="fa fa-plus"></i> @lang('admin.add')
                    </a>
                   
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
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>@lang('admin.error')</strong>
                    </div>
                    @endif
                    @if(Session::has('no_permission'))
                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                        </button>
                        <strong>@lang('admin.no_permission')</strong>
                    </div>
                    @endif
                    <table class="table" id="table">
                        <thead>
                        <tr>
                            @forelse($listing_columns as $listing_column)
                                <th>@lang('admin.' . $listing_column)</th>
                            @empty
                            @endforelse
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $key => $item)
                                <tr style="cursor:move">
                                    @forelse($listing_columns as $listing_column)
                                        <td>{{ $item->$listing_column }}</td>
                                    @empty
                                    @endforelse
                                    <td>
                                        <a class="btn btn-primary btn-sm pull-right" 
                                           href="{{ route('Edit'.$routes_suffix, $item->route) }}"
                                        > 
                                            <i class="fa fa-edit"></i> 
                                        </a>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection
