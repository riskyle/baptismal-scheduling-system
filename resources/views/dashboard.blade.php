@extends('layouts.main')
@section('content')
    <div class="wrapper" style="background-color: skyblue">
        @include('layouts.navigation')
        <div class="mx-4">
            <h4>Welcome to BaptoSchedule:</h4>
            <p>Parish Baptism Scheduler</p>
        </div>
        <section class="chat-area">
            <div id="choices" class="d-inline-flex mx-2">
                <button class="btn btn-outline-success choose-available-date mx-2">Choose Available Date!</button>
                <button class="btn btn-outline-success see-requirements mx-2">See Requirements!</button>
            </div>
            <div class="chat-box" style="background-color: skyblue">
            </div>
        </section>
    </div>
    <script>
        function scrollToBottom() {
            $(".chat-box").scrollTop($(".chat-box").prop("scrollHeight"));
        }
        $(".chat-box").on("mouseenter", () => {
            $(this).addClass("active");
        });

        $(".chat-box").on("mouseleave", () => {
            $(this).removeClass("active");
        });

        function timeFormatter(time) {
            var splitTime = time.split(":")
            var hours = parseInt(splitTime[0]);
            var minutes = parseInt(splitTime[1]);
            var d = new Date()
            d.setHours(hours);
            d.setMinutes(minutes);
            return formattedTime = d.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        }

        function dateFormatter(value) {
            const date = new Date(value);

            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
            };

            return new Intl.DateTimeFormat('en-US', options).format(date);
        }

        $(".see-requirements").on("click", (e) => {
            e.preventDefault()
            var others = `
            <div class="chat outgoing">
                <div class="details">
                    <p>See Requirements!</p>
                </div>  
            </div>
            <div class="chat incoming">
                <img src="http://127.0.0.1:8000/st_isidore.jpg" width="60" height="60" alt="" />
                <div class="details">
                    <p><a href="https://docs.google.com/document/d/1iLpWR1l2vFtbPvI2Nrx9FxQ-kY99mktA/edit" target="__blank">BaptismForm.pdf</a></p>
                </div>  
            </div>
                    <button class="btn btn-outline-primary" id="other-reminder-button">Other reminders!</button>
                    `
            $(".chat-box").append(others)
            scrollToBottom()
        })

        $(document).on("click", async (e) => {
            if (e.target.id === "other-reminder-button") {
                e.preventDefault()
                var remindersText = `
                <div class="chat outgoing">
                    <div class="details">
                        <p>Other Reminders!</p>
                    </div>  
                </div>
                <div class="chat incoming">
                    <img src="http://127.0.0.1:8000/st_isidore.jpg" width="60" height="60" alt="" />
                    <div class="details">
                        <p>
                                Reminders!!
                                </br>
                                1. Please print and fill up the form above and bring during the seminar.
                                </br>
                                2. Bring Baptism Fee: 560.00 pesos
                                </br>
                                3. Those unable to bring requirements will not be accomodated during the baptism day.
                                </br>
                                4. Those  who are not able to attend the seminar will not be accomodated during the baptism day (ATTENDANCE IS A MUST)
                                </br>
                        </p>
                    </div>  
                </div>
                <div class="chat incoming">
                    <img src="http://127.0.0.1:8000/st_isidore.jpg" width="60" height="60" alt="" />
                    <div class="details">
                        <p>
                            Thank you, Parishoners!
                            </br>
                            For any other concerns, 
                            </br>
                            reach us @ +639666550816 or baptosched@gmail.com
                        </p>
                    </div>  
                </div>
                <div class="chat incoming">
                    <img src="http://127.0.0.1:8000/st_isidore.jpg" width="60" height="60" alt="" />
                    <div class="details">
                        <p>
                            We sent you a message in your gmail account as proof of your booking for baptoschedule. Please check your notification.
                            </br>
                            Thank you.
                        </p>
                    </div>  
                </div>
                `
                $(".chat-box").append(remindersText)
                scrollToBottom()
            } else if (e.target.id === "sched-cancel-button") {
                e.preventDefault()
                $("#confirmation").remove()
            } else if (e.target.id === "select-sched") {
                e.preventDefault()
                scrollToBottom()
                var schedId = e.target.getAttribute("data-attr")
                try {
                    const response = await axios.get("/get-sched/", {
                        params: {
                            sched_id: schedId
                        }
                    })
                    console.log(response)
                    let status = response.data.status
                    if (status == true) {
                        var confirmation = `
                            <div class="chat incoming">
                                <img src="http://127.0.0.1:8000/st_isidore.jpg" width="60" height="60" alt="" />
                                <div class="details">
                                    <p>
                                       You have already Scheduled.
                                    </p>
                                </div>  
                            </div>`
                    } else {
                        var _s = response.data.sched
                        if (_s.sched_slot == 0) {
                            var confirmation = `
                            <div class="chat incoming">
                                <img src="http://127.0.0.1:8000/st_isidore.jpg" width="60" height="60" alt="" />
                                <div class="details">
                                    <p>
                                        Sched is Full
                                    </p>
                                </div>  
                            </div>
                            `
                        } else {
                            var confirmation = `
                        <div id="confirmation" class="chat outgoing">
                            <div class="details">
                                <p>
                                    Note: We would like to tell you that after you confirm this there will be a receipt that will send in to your GMAIL, validation that you are scheduled.
                                    </br>
                                    </br>
                                    Would you like to confirm this schedule?
                                    On ${dateFormatter(_s.sched_date)} at ${timeFormatter(_s.sched_date)}
                                    </br>
                                    <span class="d-inline-flex">
                                        <button class="btn btn-outline-success me-2" id="sched-confirm-button" data-attr="${_s.id}">Confirm</button>
                                        <button class="btn btn-outline-secondary flex-wrap " id="sched-cancel-button">Cancel</button>
                                    </span>
                                </p>
                            </div>  
                        </div>
                        `
                        }
                    }
                    $(".chat-box").append(confirmation)
                    scrollToBottom()
                } catch (error) {
                    console.error(error)
                }
            } else if (e.target.id === "sched-confirm-button") {
                var schedId = e.target.getAttribute("data-attr")
                e.target.textContent = "Please Wait..."
                $('button.btn').prop('disabled', true);
                try {
                    const getResponse = await axios.get("get-sched/", {
                        params: {
                            sched_id: schedId
                        }
                    })
                    /*
                        but first you need to comply this following to succesfuly booked.</h4>
                    <ol>
                        <li>Pay first</li>
                        <li>Attend Seminar</li>
                        <li>Comply Requirements</li>
                    </ol>
                        */
                    let data = getResponse.data.sched
                    let time = timeFormatter(data.sched_date)
                    let date = dateFormatter(data.sched_date)
                    const postResponse = await axios.post("/store-msg", {
                        user_message: "Schedule Confirmed",
                        bot_response: `Scheduled selected on ${date} at ${time}`,
                        sched_id: data.id,
                    })
                    console.log(postResponse)
                    e.target.textContent = "Confirm"
                    $('button.btn').prop('disabled', false);
                    reload()
                } catch (error) {
                    console.error(error)
                }
            }
        })

        $(".choose-available-date").on("click", async (e) => {
            e.preventDefault()
            try {
                const response = await axios.post("/store-msg", {
                    user_message: $(".choose-available-date")[0].textContent,
                    bot_response: 1,
                })
                console.log(response)
                reload()
                scrollToBottom()
            } catch (error) {
                console.error(error)
            }
        })

        async function reload() {
            try {
                const response = await axios.get("/get-msg")
                $(".chat-box").html(response.data)
                scrollToBottom();
            } catch (error) {
                console.error(error)
            }
        }
        reload()
    </script>
@endsection
