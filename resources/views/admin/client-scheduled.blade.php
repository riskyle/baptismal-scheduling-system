@extends('admin.layouts.main')
@section('content')
    <section class="content text-dark">
        <div class="container-fluid">
            <div class="card card-outline rounded-0 card-navy">
                <div class="card-header">
                    <h3 class="card-title">List of Clients</h3>
                    <div class="card-tools m-auto">
                        <div class="dropdown me-5">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Filter Month
                            </button>
                            <div class="dropdown-menu dropdown-menu-scrollable" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('admin.client-scheduled') }}">All</a>
                                <a class="dropdown-item" href="{{ route('admin.client-scheduled') }}?month=1">January</a>
                                <a class="dropdown-item" href="{{ route('admin.client-scheduled') }}?month=2">February</a>
                                <a class="dropdown-item" href="{{ route('admin.client-scheduled') }}?month=3">March</a>
                                <a class="dropdown-item" href="{{ route('admin.client-scheduled') }}?month=4">April</a>
                                <a class="dropdown-item" href="{{ route('admin.client-scheduled') }}?month=5">May</a>
                                <a class="dropdown-item" href="{{ route('admin.client-scheduled') }}?month=6">June</a>
                                <a class="dropdown-item" href="{{ route('admin.client-scheduled') }}?month=7">July</a>
                                <a class="dropdown-item" href="{{ route('admin.client-scheduled') }}?month=8">August</a>
                                <a class="dropdown-item" href="{{ route('admin.client-scheduled') }}?month=9">September</a>
                                <a class="dropdown-item" href="{{ route('admin.client-scheduled') }}?month=10">October</a>
                                <a class="dropdown-item" href="{{ route('admin.client-scheduled') }}?month=11">November</a>
                                <a class="dropdown-item" href="{{ route('admin.client-scheduled') }}?month=12">December</a>
                            </div>
                        </div>
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
                                        <td class='is_paid{{ $scheduledUser->id }}'>
                                            {{ $scheduledUser->paid_at != null ? $scheduledUser->paid_at : 'Unpaid' }}
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
                                                    <div class="dropdown-divider paid"></div>
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
                    <button type="button" class="btn btn-primary" id="confirmed-button" style="display: none"
                        data-dismiss="modal">Confirmed Booked</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var clientScheduled = $('.table').dataTable({
                columnDefs: [{
                    orderable: false,
                    targets: [4]
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

                    let schedUser = response.data.scheduledUser

                    $("#paid,  #seminar, #requirements").prop("disabled", false);

                    $("#paid, #seminar, #requirements").prop("checked", false);

                    $('#confirmed-button').hide()

                    if (schedUser.paid_at != null && schedUser.is_seminar && schedUser
                        .is_requirements) {
                        $('#confirmed-button').show()
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
                const currentDate = new Date();
                const formattedDate = currentDate.toLocaleDateString('en-US', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });
                $('.is_paid' + id).html(formattedDate)
                $('.paid_data').hide();
                $('.paid').hide();
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

            $('#confirmed-button').on('click', async function() {
                $("#requirements").prop("disabled", true);
                $("#requirements").prop("checked", true);
                await throughCheckList(4, id)
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
