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

                                                <button class="check-list btn btn-primary bg-white border-0" id='check-list'
                                                    data-toggle="modal" data-target="#checkListModal"
                                                    data-id='{{ $scheduledUser->id }}'><span
                                                        class="fa fa-check text-success"></span>
                                                    Check List</button>
                                                <div class="dropdown-divider"></div>
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
    <!-- Modal -->
    <div class="modal fade" id="checkListModal" tabindex="-1" role="dialog" aria-labelledby="checkListModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkListModalLabel">Check List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="checkbox" name="Paid" id="paid" />
                    <label for="paid">Paid</label><br />
                    <input type="checkbox" name="Seminar" id="seminar" />
                    <label for="seminar">Seminar</label><br />
                    <input type="checkbox" name="Requirements" id="requirements" />
                    <label for="seminar">Requirements</label><br />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
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

            var id = ''
            $('.check-list').on('click', async function() {
                id = $(this).attr('data-id')

                try {
                    const response = await axios.post('/check-list', {
                        purpose: 0,
                        id: id,
                    })
                    console.log(response.data.scheduledUser)

                    let schedUser = response.data.scheduledUser

                    $("#paid,  #seminar, #requirements").prop("disabled", false);

                    $("#paid, #seminar, #requirements").prop("checked", false);

                    if (schedUser.paid_at != null && schedUser.is_seminar && schedUser
                        .is_requirements) {
                        $("#paid,  #seminar, #requirements").prop("disabled", true);
                        $("#paid, #seminar, #requirements").prop("checked", true);
                    } else if (schedUser.paid_at != null && schedUser.is_seminar) {
                        $("#paid,  #seminar").prop("disabled", true);
                        $("#paid, #seminar").prop("checked", true);
                    } else if (schedUser.is_requirements && schedUser.is_seminar) {
                        $("#requirements,  #seminar").prop("disabled", true);
                        $("#requirements, #seminar").prop("checked", true);
                    } else if (schedUser.paid_at != null && schedUser.is_requirements) {
                        $("#paid,  #requirements").prop("disabled", true);
                        $("#paid, #requirements").prop("checked", true);
                    } else if (schedUser.paid_at != null) {
                        $("#paid").prop("disabled", true);
                        $("#paid").prop("checked", true);
                    } else if (schedUser.is_seminar) {
                        $("#seminar").prop("disabled", true);
                        $("#seminar").prop("checked", true);
                    } else if (schedUser.is_requirements) {
                        $("#requirements").prop("disabled", true);
                        $("#requirements").prop("checked", true);
                    }

                } catch (error) {
                    console.log(error)
                }

            });

            $('#paid').on('click', async function() {
                $("#paid").prop("disabled", true);
                $("#paid").prop("checked", true);
                await throughCheckList(1, id)
            })

            $('#seminar').on('click', async function() {
                $("#seminar").prop("disabled", true);
                $("#seminar").prop("checked", true);
                await throughCheckList(2, id)
            })

            $('#requirements').on('click', async function() {
                $("#requirements").prop("disabled", true);
                $("#requirements").prop("checked", true);
                await throughCheckList(3, id)
            })
        })

        async function throughCheckList(purpose, id) {
            try {
                const response = await axios.post('/check-list', {
                    purpose: purpose,
                    id: id,
                })
            } catch (error) {
                console.log(error)
            }
        }
    </script>
@endsection
