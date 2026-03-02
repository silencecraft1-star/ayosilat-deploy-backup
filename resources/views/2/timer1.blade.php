<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Timer</title>
</head>
<body class="px-3 pt-5 bg-primary d-flex justify-content-center align-items-center" style="height: 650px;">
    <div class="container-fluid p-1 py-4 bg-light shadow rounded border-dark">
        <div class="container-fluid d-flex justify-content-end align-items-center mb-1">
            <i class="fa-solid fa-gear" style="cursor: pointer;" data-toggle="modal" data-target="#timerModal"></i>
        </div>
        <div class="row" style="height: 80px;">
            <div class="col text-center text-primary">
                Hours
                <div id="hours" class="h5 container-fluid shadow p-3 text-center align-middle text-dark" style="width: 75px;">
                    00
                </div>
            </div>
            <div class="col-1 text-center text-primary d-flex justify-content-center text-primary fw-bold align-items-center pt-4" style="height: 100%;">
                :
            </div>
            <div class="col text-center text-primary">
                Minutes
                <div id="minutes" class="h5 container-fluid shadow p-3 text-center align-middle text-dark" style="width: 75px;">
                    00
                </div>
            </div>
            <div class="col-1 text-center text-primary d-flex justify-content-center text-primary fw-bold align-items-center pt-4" style="height: 100%;">
                :
            </div>
            <div class="col text-center text-primary">
                Seconds
                <div id="seconds" class="h5 container-fluid shadow p-3 text-center align-middle text-dark" style="width: 75px;">
                    00
                </div>
            </div>
        </div>
        <div class="container-fluid d-flex justify-content-center my-2">
            <button id="pauseBtn" class="btn btn-primary mx-3 mt-2 px-4">Pause</button>  
            <button id="startBtn" class="btn btn-danger mx-3 mt-2 px-4">Start</button>
            <button id="stopBtn" class="btn btn-primary mx-3 mt-2 px-4">Stop</button>
        </div>
    </div>

    <div class="modal fade" id="timerModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Setting Timer</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                      </button>
                </div>
                <div class="modal-body">
                    Timer
                    <input type="time" class="form-control" placeholder="Timer">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary fw-bold">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let timer;
        let hours = 0;
        let minutes = 0;
        let seconds = 0;

        function updateTimer() {
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
        }

        function startTimer() {
            timer = setInterval(() => {
                seconds++;
                if (seconds === 60) {
                    seconds = 0;
                    minutes++;
                    if (minutes === 60) {
                        minutes = 0;
                        hours++;
                    }
                }
                updateTimer();
            }, 1000);
        }

        function pauseTimer() {
            clearInterval(timer);
        }

        function stopTimer() {
            clearInterval(timer);
            hours = 0;
            minutes = 0;
            seconds = 0;
            updateTimer();
        }

        document.getElementById('startBtn').addEventListener('click', startTimer);
        document.getElementById('pauseBtn').addEventListener('click', pauseTimer);
        document.getElementById('stopBtn').addEventListener('click', stopTimer);
    </script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
