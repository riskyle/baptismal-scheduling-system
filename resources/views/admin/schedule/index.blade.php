@extends('admin.layouts.main')
@section('content')
    <section class="content text-dark">
        <div class="container-fluid">
            <div class="card card-outline rounded-0 card-navy">
                <div class="card-header">
                    <h3 class="card-title">List of Schedules</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.schedule.create') }}" id="create_new" class="btn btn-flat btn-primary"><span
                                class="fas fa-plus"></span>
                            Create New</a>
                    </div>
                </div>
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
                                    <th>Schedule Date</th>
                                    <th>Schedule Time</th>
                                    <th>Slots</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedules as $key => $schedule)
                                    <tr>
                                        <td class="text-center">{{ ++$key }}</td>
                                        <td> {{ Carbon\Carbon::parse($schedule->sched_date)->format('F d, Y') }}</td>
                                        <td> {{ Carbon\Carbon::parse($schedule->sched_date)->format('h:s a') }}</td>
                                        <td> {{ $schedule->sched_slot }}</td>
                                        <td align="center">
                                            <button type="button"
                                                class="btn btn-flat p-1 btn-default btn-sm dropdown-toggle dropdown-icon"
                                                data-toggle="dropdown">
                                                Action
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                {{-- <a class="dropdown-item view_data" href=""><span
                                                        class="fa fa-eye text-dark"></span> View</a>
                                                <div class="dropdown-divider"></div> --}}
                                                <a class="dropdown-item edit_data"
                                                    href="{{ route('admin.schedule.edit', $schedule->id) }}"><span
                                                        class="fa fa-edit text-primary"></span> Edit</a>
                                                <div class="dropdown-divider"></div>
                                                <form action="{{ route('admin.schedule.delete', $schedule->id) }}"
                                                    method="post" id="delete">
                                                    @csrf
                                                    @method('PATCH')
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
            // $('.delete_data').click(function() {
            //     _conf("Are you sure to delete this client permanently?", "delete_client", [$(this).attr(
            //         'data-id')])
            // })
            $('.table').dataTable({
                columnDefs: [{
                    orderable: false,
                    targets: [3]
                }],
                order: [0, 'asc']
            });
            $('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
        })

        // function delete_client($id) {
        //     start_loader();
        //     $.ajax({
        //         url: _base_url_ + "classes/Master.php?f=delete_client",
        //         method: "POST",
        //         data: {
        //             id: $id
        //         },
        //         dataType: "json",
        //         error: err => {
        //             console.log(err)
        //             alert_toast("An error occured.", 'error');
        //             end_loader();
        //         },
        //         success: function(resp) {
        //             if (typeof resp == 'object' && resp.status == 'success') {
        //                 location.reload();
        //             } else {
        //                 alert_toast("An error occured.", 'error');
        //                 end_loader();
        //             }
        //         }
        //     })
        // }
    </script>
@endsection
