@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Username')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Role')</th>
                                    <th>@lang('Joined At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admins as $admin)
                                    <tr>
                                        <td>{{ $admin->name }}</td>
                                        <td>{{ $admin->username }}</td>
                                        <td>{{ $admin->email }}</td>
                                        <td>{{ @$admin->role->name ?? __('Super Admin') }}</td>
                                        <td>{{ showDateTime($admin->created_at) }}</td>
                                        <td>
                                            <div class="button--group">
                                                <button type="button" class="btn btn-sm btn-outline--primary editBtn"
                                                    data-id="{{ $admin->id }}"
                                                    data-name="{{ $admin->name }}"
                                                    data-username="{{ $admin->username }}"
                                                    data-email="{{ $admin->email }}"
                                                    data-role_id="{{ $admin->role_id }}">
                                                    <i class="la la-pencil"></i> @lang('Edit')
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline--danger removeBtn" data-id="{{ $admin->id }}">
                                                    <i class="la la-trash"></i> @lang('Remove')
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">@lang('No admins found')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($admins->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($admins) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Add/Update Admin Modal --}}
    <div id="adminModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Email')</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Username')</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Password') <small class="text--primary">(@lang('Leave empty for no change'))</small></label>
                            <input type="password" class="form-control" name="password">
                        </div>
                        <div class="form-group">
                            <label>@lang('Role')</label>
                            <select name="role_id" class="form-control" required>
                                <option value="0" data-permissions='["*"]'>@lang('Super Admin')</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" data-permissions='@json($role->permissions)'>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="text--black">@lang('Role Permissions')</label>
                            <div id="permissions_preview" class="p-2 border rounded bg-light d-flex flex-wrap gap-1 text--black">
                                <span class="badge badge--info">@lang('Full Access')</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Remove Admin Modal --}}
    <div id="removeModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Removal Confirmation')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>@lang('Are you sure to remove this admin?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--danger">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn btn-sm btn-outline--primary addBtn"><i class="las la-plus"></i> @lang('Add New Admin')</button>
@endpush

@push('script')
    <script>
        (function($){
            "use strict";
            let modal = $('#adminModal');
            
            const permissionMap = {
                'admin.users.*': 'Manage Users',
                'admin.deposit.*': 'Manage Deposits',
                'admin.withdraw.*': 'Manage Withdrawals',
                'admin.plan.*': 'Manage Plans',
                'admin.setting.*': 'General Setting',
                'admin.frontend.*': 'Manage Frontend',
                'admin.ticket.*': 'Manage Support Tickets',
                'admin.system.*': 'System Info',
                '*': 'Full Access'
            };

            function showPermissions(roleElement) {
                let permissions = $(roleElement).find(':selected').data('permissions');
                let html = '';
                if (permissions && permissions.length > 0) {
                    permissions.forEach(p => {
                        let text = permissionMap[p] || p;
                        html += `<span class="badge badge--info text--black">${text}</span>`;
                    });
                } else {
                    html = '<span class="text--black small">@lang('No permissions allocated')</span>';
                }
                $('#permissions_preview').html(html);
            }

            $('select[name=role_id]').on('change', function() {
                showPermissions(this);
            });

            $('.addBtn').on('click', function(){
                modal.find('.modal-title').text("@lang('Add New Admin')");
                modal.find('form').attr('action', `{{ route('admin.staff.admin.store') }}`);
                modal.find('input[name=name]').val('');
                modal.find('input[name=email]').val('');
                modal.find('input[name=username]').val('');
                modal.find('select[name=role_id]').val(0);
                showPermissions(modal.find('select[name=role_id]'));
                modal.modal('show');
            });

            $('.editBtn').on('click', function(){
                let data = $(this).data();
                modal.find('.modal-title').text("@lang('Update Admin')");
                modal.find('form').attr('action', `{{ route('admin.staff.admin.store', '') }}/${data.id}`);
                modal.find('input[name=name]').val(data.name);
                modal.find('input[name=email]').val(data.email);
                modal.find('input[name=username]').val(data.username);
                modal.find('select[name=role_id]').val(data.role_id);
                showPermissions(modal.find('select[name=role_id]'));
                modal.modal('show');
            });

            $('.removeBtn').on('click', function(){
                let modal = $('#removeModal');
                modal.find('form').attr('action', `{{ route('admin.staff.admin.remove', '') }}/${$(this).data('id')}`);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
