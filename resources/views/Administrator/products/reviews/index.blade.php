@extends('layouts.admin')
@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <form method="post" action="{{ route('Multi') }}" id="multi-form">
            @csrf
            <input type="hidden" name="table" value="{{ $main_table }}">
            <div class="x_title">
                <h2>@lang('admin.routes.' . $routes_suffix)</h2>
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
                @if(Session::has('no_permission'))
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>@lang('admin.no_permission')</strong>
                </div>
                @endif
                @if(Session::has('cant_remove'))
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <ul>
                        @foreach(Session::get('cant_remove') as $cant_remove_cat_id)
                            @php 
                                $title = DB::table($translates_table)->select('title')
                                        ->where('parent_id',$cant_remove_cat_id)
                                        ->where('lang',$configuration->admin_lang)->first()->title;
                            @endphp
                            <li>
                                <strong>{{ $title }}</strong> - @lang('admin.childs_error')
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <table class="table">
                    <thead>
                    <tr>
                        @forelse($listing_columns as $listing_column)
                            <th>@lang('admin.' . $listing_column)</th>
                        @empty
                        @endforelse
                        <th>@lang('admin.stars')</th>
                        <th>@lang('admin.routes.Products')</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $key => $item)
                            <tr>
                                @forelse($listing_columns as $listing_column)
                                    @if($listing_column === 'sort')
                                        <td class="sort" id="sort{{ $key }}" data-id="{{ $item->id }}" data-ordering ="{{ $item->sort }}">
                                            {{ ++$key }}
                                        </td>
                                    @elseif($listing_column === 'status')
                                        <td>
                                            <div class="iradio">
                                                <input type="checkbox" 
                                                       data-id="{{ $item->id }}" 
                                                       data-table="{{ $main_table }}"
                                                       data-column="status"
                                                       {{ $item->status ? 'checked' : '' }} 
                                                       class="js-switch change" 
                                                />
                                            </div>
                                        </td>
                                    @else
                                        <td>{{ $item->$listing_column }}</td>
                                    @endif
                                @empty
                                @endforelse
                                <td>
                                    @for($s=1; $s <= 5; $s++)
                                        <i class="fa fa-star" style="{{ $item->stars < $s ? 'opacity: 0.2': '' }}"></i>
                                    @endfor
                                </td>
                                <td>
                                    <a href="{{ route('EditProducts', $item->product_id) }}" 
                                       style="color: orange;"
                                       target="_blank"
                                    >
                                        {{ $item->title }}
                                    </a>
                                </td>
                                <td>
                                    <button class="btn btn-danger btn-sm pull-right delete-review" 
                                            data-id="{{ $item->id }}"
                                            data-table="{{ $main_table }}"
                                    >
                                        <i class="fa fa-trash"></i> 
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($listing_columns) + 3 }}">
                                    <div class="alert alert-warning text-center">
                                        @lang('admin.product_not_found')
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
        <div>
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    
    // წაშლა
    $('.delete-review').click(function (event) {
    
        event.preventDefault();

        let _this = $(this);
        let id = $(this).data('id');
        let table = $(this).data('table');

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
                        url: "{{ route('Remove'.$routes_suffix) }}",
                        type: 'post',
                        //dataType: 'json',
                        data: {id: id, table: table}
                    }).done(function (data) {

                        new PNotify({
                            text: data.text,
                            type: data.type,
                            styling: 'bootstrap3'
                        });

                        if (data.status == 1) 
                        {
                            _this.parents('tr').hide().remove();
                        } 

                    });
                }
            }
        });

    });

    
</script>
@endpush


                            
                            
