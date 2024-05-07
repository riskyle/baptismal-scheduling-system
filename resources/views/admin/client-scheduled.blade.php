@extends('admin.layouts.main')
@section('content')
    <section class="content text-dark">
        <div class="container-fluid">
            <div class="card card-outline rounded-0 card-navy">
                {{-- <div class="card-header">
                    <h3 class="card-title">List of Schedules</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.schedule.create') }}" id="create_new" class="btn btn-flat btn-primary"><span
                                class="fas fa-plus"></span>
                            Create New</a>
                    </div>
                </div> --}}
                <div class="card-body">
                    <div class="container-fluid">
                        <table class="table table-hover table-striped table-bordered" id="list">
                            <colgroup>
                                <col width="5%">
                                <col width="15%">
                                <col width="25%">
                                <col width="25%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Client Name</th>
                                    <th>Date Scheduled</th>
                                    <th>Paid Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($scheduledUsers as $key => $scheduledUser)
                                    <tr>
                                        <td class="text-center">{{ ++$key }}</td>
                                        <td> {{ $scheduledUser->user->name }}</td>
                                        <td> {{ Carbon\Carbon::parse($scheduledUser->schedule->sched_date)->format('F d, Y @h:i a') }}
                                        </td>
                                        <td> {{ $scheduledUser->paid_at != null ? Carbon\Carbon::parse($scheduledUser->paid_at)->format('F d, Y') : 'Unpaid' }}
                                        </td>
                                        <td align="center">
                                            <button type="button"
                                                class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon"
                                                data-toggle="dropdown">
                                                Action
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if ($scheduledUser->paid_at == null)
                                                    <form
                                                        action="{{ route('admin.client-scheduled.paid', $scheduledUser->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="dropdown-item paid_data" type="submit"><span
                                                                class="fa fa-money-bill-wave text-info"></span>
                                                            Paid</button>
                                                    </form>
                                                    <div class="dropdown-divider"></div>
                                                @endif
                                                <form
                                                    action="{{ route('admin.client-schedule.delete', $scheduledUser->id) }}"
                                                    method="post" id="delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item delete_data" type="submit"><span
                                                            class="fa fa-trash text-danger"></span>
                                                        Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('.table').dataTable({
                columnDefs: [{
                    orderable: false,
                    targets: [3]
                }],
                order: [0, 'asc']
            });
            $('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
        })
    </script>
@endsection
