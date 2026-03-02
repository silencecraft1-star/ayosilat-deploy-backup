<script src="{{ asset('js/app.js') }}"></script>
<script>
    // console.log(window.process.env);
    $('#timer1').text('00:00');
    let timerInterval;
    let currentTime = 0; // Simpan waktu dalam detik
    let isPaused = false;

    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }

    function startTimer() {
        if (!isPaused) {
            currentTime = 0; // Reset waktu ke 00:00 saat start
        }
        isPaused = false;
        clearInterval(timerInterval); // Hentikan interval sebelumnya (jika ada)
        timerInterval = setInterval(() => {
            currentTime++;
            var Timers = formatTime(currentTime);
            $('#timer1').text(Timers);
        }, 1000);
    }

    function pauseTimer() {
        clearInterval(timerInterval);
        isPaused = true;
    }

    function resumeTimer() {
        if (isPaused) {
            isPaused = false;
            clearInterval(timerInterval); // Hentikan interval sebelumnya (jika ada)
            timerInterval = setInterval(() => {
                currentTime++;
                var Timers = formatTime(currentTime);
                $('#timer1').text(Timers);
            }, 1000);
        }
    }

    function resetTimer() {
        clearInterval(timerInterval);
        currentTime = 0;
        isPaused = false;
        var Timers = formatTime(currentTime);
        $('#timer1').text(Timers);
    }
    let isModalLaunched = false;
    let lastmodal = "null";

    let totalPoint1 = 0;
    let totalPoint2 = 0;

    function socketVerifikasi(response) {
        if (response.notif != "not") {
            // var name = "modal" + response.notif;
            // let myModal = new bootstrap.Modal(document.getElementById(name));
            // lastmodal = name;
            let juri1 = document.getElementById('juri1-' + response.status);
            let juri2 = document.getElementById('juri2-' + response.status);
            let juri3 = document.getElementById('juri3-' + response.status);

            console.log(response.data);
            let master = response.data;
            master.forEach(data => {
                if (juri1) {
                    if (data.id_juri == "Juri_1") {
                        if (data.score == "biru") {
                            juri1.classList.remove('br', 'by',
                                'bn');
                            juri1.classList.add('bb');
                        } else if (data.score == "merah") {
                            juri1.classList.remove('bb', 'by',
                                'bn');
                            juri1.classList.add('br');
                        } else if (data.score == "invalid") {
                            juri1.classList.remove('bb', 'br',
                                'bn');
                            juri1.classList.add('by');
                        } else {
                            juri3.classList.remove('bb', 'br',
                                'by');
                            juri3.classList.add('bn');
                        }
                    }

                }
                if (juri2) {
                    if (data.id_juri == "Juri_2") {
                        if (data.score == "biru") {
                            juri2.classList.remove('br', 'by',
                                'bn');
                            juri2.classList.add('bb');
                        } else if (data.score == "merah") {
                            juri2.classList.remove('bb', 'by',
                                'bn');
                            juri2.classList.add('br');
                        } else if (data.score == "invalid") {
                            juri2.classList.remove('bb', 'br',
                                'bn');
                            juri2.classList.add('by');
                        } else {
                            juri3.classList.remove('bb', 'br',
                                'by');
                            juri3.classList.add('bn');
                        }
                    }
                }
                if (juri3) {
                    if (data.id_juri == "Juri_3") {
                        if (data.score == "biru") {
                            juri3.classList.remove('br', 'by',
                                'bn');
                            juri3.classList.add('bb');
                        } else if (data.score == "merah") {
                            juri3.classList.remove('bb', 'by',
                                'bn');
                            juri3.classList.add('br');
                        } else if (data.score == "invalid") {
                            juri3.classList.remove('bb', 'br',
                                'bn');
                            juri3.classList.add('by');
                        } else {
                            juri3.classList.remove('bb', 'br',
                                'by');
                            juri3.classList.add('bn');
                        }
                    }

                }
                //console.log(response);
            });

            // launchModal(myModal);
        }
    }

    function socketScore(response) {
        //console.log(response);
        if (response.binaan1 == 1) {
            $('#binaan1').attr("src", "../assets/Assets/pointing_hand_red.png")
            $('#binaan2').attr("src", "../assets/Assets/peace_hand.png")
        } else if (response.binaan1 == 2) {
            $('#binaan1').attr("src", "../assets/Assets/pointing_hand_red.png")
            $('#binaan2').attr("src", "../assets/Assets/peace_hand_red.png")
        } else if (response.binaan1 <= 0) {
            $('#binaan1').attr("src", "../assets/Assets/pointing_hand.png")
            $('#binaan2').attr("src", "../assets/Assets/peace_hand.png")
        }

        if (response.teguranTotal1 == 1) {
            $('#teguran1').attr("src", "../assets/Assets/pointing_hand_red.png")
            $('#teguran2').attr("src", "../assets/Assets/peace_hand.png")
        } else if (response.teguranTotal1 == 2) {
            $('#teguran1').attr("src", "../assets/Assets/pointing_hand_red.png")
            $('#teguran2').attr("src", "../assets/Assets/peace_hand_red.png")
        } else if (response.teguranTotal1 <= 0) {
            $('#teguran1').attr("src", "../assets/Assets/pointing_hand.png")
            $('#teguran2').attr("src", "../assets/Assets/peace_hand.png")
        }

        if (response.totalPeringatan1 == 0) {
            $('#peringatan1').attr("src", "../assets/Assets/raising_hand.png")
            $('#peringatan2').attr("src", "../assets/Assets/raising_hand.png")
            $('#peringatan3').attr("src", "../assets/Assets/raising_hand.png")
        } else if (response.totalPeringatan1 <= 1) {
            $('#peringatan1').attr("src", "../assets/Assets/raising_hand_red.png")
            $('#peringatan2').attr("src", "../assets/Assets/raising_hand.png")
            $('#peringatan3').attr("src", "../assets/Assets/raising_hand.png")
        } else if (response.totalPeringatan1 <= 2) {
            $('#peringatan1').attr("src", "../assets/Assets/raising_hand_red.png")
            $('#peringatan2').attr("src", "../assets/Assets/raising_hand_red.png")
            $('#peringatan3').attr("src", "../assets/Assets/raising_hand.png")
        } else if (response.totalPeringatan1 <= 3) {
            $('#peringatan1').attr("src", "../assets/Assets/raising_hand_red.png")
            $('#peringatan2').attr("src", "../assets/Assets/raising_hand_red.png")
            $('#peringatan3').attr("src", "../assets/Assets/raising_hand_red.png")
        }

        if (response.binaan2 == 1) {
            $('#mbinaan1').attr("src", "../assets/Assets/pointing_hand_red.png")
            $('#mbinaan2').attr("src", "../assets/Assets/peace_hand.png")
        } else if (response.binaan2 == 2) {
            $('#mbinaan1').attr("src", "../assets/Assets/pointing_hand_red.png")
            $('#mbinaan2').attr("src", "../assets/Assets/peace_hand_red.png")
        } else if (response.binaan2 <= 0) {
            $('#mbinaan1').attr("src", "../assets/Assets/pointing_hand.png")
            $('#mbinaan2').attr("src", "../assets/Assets/peace_hand.png")
        }


        if (response.teguranTotal2 == 1) {
            $('#mteguran1').attr("src", "../assets/Assets/pointing_hand_red.png")
            $('#mteguran2').attr("src", "../assets/Assets/peace_hand.png")
        } else if (response.teguranTotal2 == 2) {
            $('#mteguran1').attr("src", "../assets/Assets/pointing_hand_red.png")
            $('#mteguran2').attr("src", "../assets/Assets/peace_hand_red.png")
        } else if (response.teguranTotal2 <= 0) {
            $('#mteguran1').attr("src", "../assets/Assets/pointing_hand.png")
            $('#mteguran2').attr("src", "../assets/Assets/peace_hand.png")
        }

        if (response.totalPeringatan2 == 0) {
            $('#mperingatan1').attr("src", "../assets/Assets/raising_hand.png")
            $('#mperingatan2').attr("src", "../assets/Assets/raising_hand.png")
            $('#mperingatan3').attr("src", "../assets/Assets/raising_hand.png")
        } else if (response.totalPeringatan2 <= 1) {
            $('#mperingatan1').attr("src", "../assets/Assets/raising_hand_red.png")
            $('#mperingatan2').attr("src", "../assets/Assets/raising_hand.png")
            $('#mperingatan3').attr("src", "../assets/Assets/raising_hand.png")
        } else if (response.totalPeringatan2 <= 2) {
            $('#mperingatan1').attr("src", "../assets/Assets/raising_hand_red.png")
            $('#mperingatan2').attr("src", "../assets/Assets/raising_hand_red.png")
            $('#mperingatan3').attr("src", "../assets/Assets/raising_hand.png")
        } else if (response.totalPeringatan2 <= 3) {
            $('#mperingatan1').attr("src", "../assets/Assets/raising_hand_red.png")
            $('#mperingatan2').attr("src", "../assets/Assets/raising_hand_red.png")
            $('#mperingatan3').attr("src", "../assets/Assets/raising_hand_red.png")
        }

        //totalPoint1 += parseFloat(response.pukulanb) ?? 0;
        //totalPoint1 += parseFloat(response.tendanganb) ?? 0;
        totalPoint1 += parseFloat(response.jatuh1) ?? 0;
        totalPoint1 -= parseFloat(response.totalBinaan1) ?? 0;
        totalPoint1 -= parseFloat(response.teguranTotal1) ?? 0;
        if (parseFloat(response.totalPeringatan1) / 1 == 1) {
            totalPoint1 -= parseFloat(response.totalPeringatan1 * 5) ?? 0;
        } else if (parseFloat(response.totalPeringatan1) / 2 == 1) {
            totalPoint1 -= parseFloat(response.totalPeringatan1 * 5 * 3) ?? 0;
        } else if (parseFloat(response.totalPeringatan1) / 3 == 1) {
            totalPoint1 -= parseFloat(response.totalPeringatan1 * 5 * 6) ?? 0;
        }

        //totalPoint2 += parseFloat(response.pukulanm) ?? 0;
        //totalPoint2 += parseFloat(response.tendanganm) ?? 0;
        totalPoint2 += parseFloat(response.jatuh2) ?? 0;
        totalPoint2 -= parseFloat(response.totalBinaan2) ?? 0;
        totalPoint2 -= parseFloat(response.teguranTotal2) ?? 0;
        if (parseFloat(response.totalPeringatan2) / 1 == 1) {
            totalPoint2 -= parseFloat(response.totalPeringatan2 * 5) ?? 0;
        } else if (parseFloat(response.totalPeringatan2) / 2 == 1) {
            totalPoint2 -= parseFloat(response.totalPeringatan2 * 5 * 3) ?? 0;
        } else if (parseFloat(response.totalPeringatan2) / 3 == 1) {
            totalPoint2 -= parseFloat(response.totalPeringatan2 * 5 * 6) ?? 0;
        }

        //console.log(totalPoint1, totalPoint2, response.teguranTotal1, response.teguranTotal2);
        //warna Score
        // alert(` ${response.score1}, ${response.score2}, ${totalPoint1},${totalPoint2}`);
        
        if (response.score2 < response.score1) {
            $('#scorebiru').removeClass("bg-blue-50 text-blue-500");
            $('#scorebiru').css("background", "linear-gradient(to top, #0853D2, #04245c)");
            $("#scoremerah").removeClass(
                "bg-gradient-to-b from-red-800 to-red-500 text-red-50");
            $("#scoremerah").addClass("bg-red-50 text-red-500");
        } else if (response.score2 > response.score1) {
            $('#scorebiru').addClass("bg-blue-50 text-blue-500");
            $('#scorebiru').removeClass(
                "bg-gradient-to-b from-blue-800 to-cyan-500 text-blue-50");
            $("#scoremerah").addClass("bg-gradient-to-b from-red-800 to-red-500 text-red-50");
            $("#scoremerah").removeClass("bg-red-50 text-red-500");
        } else if (response.score1 == response.score2) {
            if (totalPoint1 > totalPoint2) {
                $('#scorebiru').removeClass("bg-blue-50 text-blue-500");
                $('#scorebiru').addClass(
                    "bg-gradient-to-b from-blue-800 to-cyan-500 text-blue-50");
                $("#scoremerah").removeClass(
                    "bg-gradient-to-b from-red-800 to-red-500 text-red-50");
                $("#scoremerah").addClass("bg-red-50 text-red-500");
            } else if (totalPoint1 < totalPoint2) {
                $('#scorebiru').addClass("bg-blue-50 text-blue-500");
                $('#scorebiru').removeClass(
                    "bg-gradient-to-b from-blue-800 to-cyan-500 text-blue-50");
                $("#scoremerah").addClass(
                    "bg-gradient-to-b from-red-800 to-red-500 text-red-50");
                $("#scoremerah").removeClass("bg-red-50 text-red-500");
            } else {
                $('#scorebiru').removeClass("bg-blue-50 text-blue-500");
                $("#scoremerah").removeClass("bg-red-50 text-red-500");
                $('#scorebiru').css("background", "linear-gradient(to top, #0853D2, #04245c)");
                $("#scoremerah").css("background", "linear-gradient(to top, #ff2727, #520a0a)");
            }
        } else {
            $('#scorebiru').addClass("bg-blue-50 text-blue-500");
            $('#scorebiru').removeClass(
                "bg-gradient-to-b from-blue-800 to-cyan-500 text-blue-50");
            $('#scorebiru').css("background", "linear-gradient(to top, #0853D2, #04245c)");
            $("#scoremerah").css("background", "linear-gradient(to top, #ff2727, #520a0a)");
        }

        if (response.statusPertandingan == "finish") {
            window.location.href = `redirect?arena=${response.arena}&partai=${response.partai}&role=rekapTanding`;
        }

        //// console.log(timer);
        $('#partai').text(`Partai ${response.partai}`);
        $('#infoKelas').text(response.infoKelas);
        $('#namaBiru').text(response.namaBiru);
        $('#namaMerah').text(response.namaMerah);
        $('#kontigenBiru').text(response.kontigenBiru);
        $('#kontigenMerah').text(response.kontigenMerah);
        $('#jatuh2').text('' + ' ' + response.jatuh1);
        //$('#jatuh2').text('' + ' ' + response.totalJatuhan2);
        $('#jatuh1').text('' + ' ' + response.jatuh2);
        //$('#jatuh1').text('' + ' ' + response.totalJatuhan1);
        $('#bina2').text('x' + ' ' + response.binaan1);
        $('#bina1').text('x' + ' ' + response.binaan2);
        $('#teguran2').text('x' + ' ' + response.teguran1);
        $('#teguran1').text('x' + ' ' + response.teguran2);
        $('#peringatan2').text('x' + ' ' + response.peringatan1);
        $('#peringatan1').text('x' + ' ' + response.peringatan2);
        $('#score1').text(response.score2);
        $('#score2').text(response.score1);
        $('#score2').attr('name', response.idBiru);
        $('#score1').attr('name', response.idMerah);



    }


    function babak1() {
        var babaksatu = document.getElementById('babaksatu');
        var babakdua = document.getElementById('babakdua');
        var babaktiga = document.getElementById('babaktiga');
        babakdua.classList.remove('by2');
        babaktiga.classList.remove('by2');
        babaksatu.classList.add('by2');
    }

    function babak2() {
        var babaksatu = document.getElementById('babaksatu');
        var babakdua = document.getElementById('babakdua');
        var babaktiga = document.getElementById('babaktiga');
        babakdua.classList.add('by2');
        babaktiga.classList.remove('by2');
        babaksatu.classList.remove('by2');
    }

    function babak3() {
        var babaksatu = document.getElementById('babaksatu');
        var babakdua = document.getElementById('babakdua');
        var babaktiga = document.getElementById('babaktiga');
        babakdua.classList.remove('by2');
        babaktiga.classList.add('by2');
        babaksatu.classList.remove('by2');
    }

    function websocket() {
        var arena_id = document.getElementById('arenaid').getAttribute('name');
        if (window.Echo) {
            window.Echo.connector.pusher.connection.bind('connected', function () {
                console.log("Terhubung ke Soketi!");
            });
            Echo.channel('indicator-channel')
                .listen('.indicator.triggered', (e) => {
                    //alert('aa');
                    const data = e.message;
                    if(data.arena == arena_id) {
                        indicator(data);
                        var reload = data;
    
                        try {
                            var parsedData = data;
    
                            if (parsedData.arena === arena_id && parsedData.event === "reload") {
                                window.location.reload();
                                console.log("Reload dipicu.");
                            }
                        } catch (error) {
                            console.error("Error parsing JSON:", error);
                        }
                    }
                })
                .error((error) => {
                    console.error('Error:', error);
                });
            Echo.channel('verification-channel')
                .listen('VerificationEvent', (datas) => {
                    var data = datas.message;

                    console.log(data);
                    if(data.arena == arena_id) {
                        //Check If Modal Action
                        if (data.status == "modal") {
                            var name = "modal" + data.type;
                            //let myModal = new bootstrap.Modal(document.getElementById(name));
    
                            if (data.command == "open") {
                                launchModal(name);
                            }
                            else {
                                CloseModal(name);
                            }
                            // alert(`${name}, ${isModalLaunched}` );
                        } else if (data.type == "verifikasi") {
                            socketVerifikasi(data);
                        }
                    }
                    // alert(JSON.stringify(datas.message));
                });
            Echo.channel('score-channel')
                .listen('ScoreEvent', (datas) => {
                    var data = datas.message;

                    if(data.arena == arena_id) {
                        console.log(data);
                        var idbabak = data.babak;
                        if (idbabak == 1) {
                            babak1();
                        } else if (idbabak == 2) {
                            babak2();
                        } else if (idbabak == 3) {
                            babak3();
                        }
    
                        totalPoint1 = 0;
                        totalPoint2 = 0;
    
                        socketScore(data);
                    }

                })
            Echo.channel('timer')
                .listen('TimerUpdate', (datas) => {
                    //console.log(datas);
                    const actions = datas.action;
                    var data = actions;
                    if (data.arena == arena_id) {
                        if (data.action === 'start') {
                            startTimer(); // Mulai timer
                        } else if (data.action === 'pause') {
                            pauseTimer(); // Jeda timer
                        } else if (data.action === 'resume') {
                            resumeTimer(); // Lanjutkan timer
                        } else if (data.action === 'stop') {
                            resetTimer(); // Reset timer
                        }
                    }

                });
        } else {
            console.error('Laravel Echo is not initialized.');
        }
    }

    function indicator(data) {
        var perserta_merah = document.getElementById('score1').getAttribute('name');
        var perserta_biru = document.getElementById('score2').getAttribute('name');
        var arena_id = document.getElementById('arenaid').getAttribute('name');
        if (data && data.arena == arena_id) {
            // check juri
            for (let index = 1; index < 4; index++) {
                var juris = `juri${index}`
                if (data[juris] > 0) {
                    var juri = index;
                }
            }
            // check kubu 
            let perserta = null;
            if (perserta_merah === data.id_perserta) {
                perserta = "m";
                var color = "br2";
            } else if (perserta_biru === data.id_perserta) {
                perserta = "b";
                var color = "bb2";
            }
            // Check kelas
            let kelas = null;
            if (data.keterangan === 'pukulan') {
                kelas = "p";
            } else if (data.keterangan === 'tendangan') {
                kelas = "t";
            }
            // render name
            if (juri !== null && perserta !== null && kelas !== null) {
                const id = `juri${juri}${perserta}${kelas}`;
                var action = document.getElementById(id);
            } else {
                console.log("Data tidak lengkap atau tidak valid.");
            }
            // action
            if (action) {
                action.classList.add(color);
                setTimeout(() => {
                    action.classList.remove(color);
                }, 300);
            } else {
                action.classList.remove(color);
            }
        }
    }

    function launchModal(myModal) {
        if (isModalLaunched === false) {
            isModalLaunched = true;

            let juri1 = document.getElementById('juri1-jatuhan');
            let juri2 = document.getElementById('juri2-jatuhan');
            let juri3 = document.getElementById('juri3-jatuhan');

            let juri1hukuman = document.getElementById('juri1-hukuman');
            let juri2hukuman = document.getElementById('juri2-hukuman');
            let juri3hukuman = document.getElementById('juri3-hukuman');

            juri1.classList.remove('bb', 'br', 'by');
            juri1.classList.add('bn');

            juri2.classList.remove('bb', 'br', 'by');
            juri2.classList.add('bn');

            juri3.classList.remove('bb', 'br', 'by');
            juri3.classList.add('bn');

            juri1hukuman.classList.remove('bb', 'br', 'by');
            juri1hukuman.classList.add('bn');

            juri2hukuman.classList.remove('bb', 'br', 'by');
            juri2hukuman.classList.add('bn');

            juri3hukuman.classList.remove('bb', 'br', 'by');
            juri3hukuman.classList.add('bn');

            pauseTimer()
            $(`#${myModal}`).modal('show');
        }
        // console.log(isModalLaunched);
    }

    function CloseModal(modalId) {

        if (isModalLaunched === true) {
            const modalElement = document.getElementById(modalId);
            const closeButton = modalElement.querySelector('.btn-close');
            //diusahakan hapus tanpa reload
            //window.location.reload();

            //clear Hukuman dan jatuhan verifikasi
            let juri1 = document.getElementById('juri1-jatuhan');
            let juri2 = document.getElementById('juri2-jatuhan');
            let juri3 = document.getElementById('juri3-jatuhan');

            let juri1hukuman = document.getElementById('juri1-hukuman');
            let juri2hukuman = document.getElementById('juri2-hukuman');
            let juri3hukuman = document.getElementById('juri3-hukuman');

            juri1.classList.remove('bb', 'br', 'by');
            juri1.classList.add('bn');

            juri2.classList.remove('bb', 'br', 'by');
            juri2.classList.add('bn');

            juri3.classList.remove('bb', 'br', 'by');
            juri3.classList.add('bn');

            juri1hukuman.classList.remove('bb', 'br', 'by');
            juri1hukuman.classList.add('bn');

            juri2hukuman.classList.remove('bb', 'br', 'by');
            juri2hukuman.classList.add('bn');

            juri3hukuman.classList.remove('bb', 'br', 'by');
            juri3hukuman.classList.add('bn');

            // Close modal using Bootstrap method
            $(`#${modalId}`).modal('hide');
            isModalLaunched = false;

            let timerText = $('#timer1').text();

            if (isPaused && timerText != '00:00') {
                resumeTimer(); // Lanjutkan timer
            }
        }
        // console.log(isModalLaunched);
    }

    function calldata() {
        var elemenDiv = document.getElementById("arenaid");
        var arena = elemenDiv.getAttribute("name");
        var element2 = document.getElementById("idpartai");
        var partai = element2.getAttribute("name");

        function pad(num, size) {
            let s = "000000000" + num;
            return s.substr(s.length - size);
        }

        function getselisih(inputTime, global) {
            let globalSplit = global.split(':');

            let now = global;
            let days = new Date();
            let nowHours = globalSplit[0];
            let nowMinutes = globalSplit[1];
            let nowSeconds = globalSplit[2];

            if (inputTime) {
                let [inputHours, inputMinutes, inputSeconds] = inputTime.split(':').map(Number);

                let inputDate = new Date(days.getFullYear(), days.getMonth(), days.getDate(), inputHours, inputMinutes,
                    inputSeconds);
                let nowDate = new Date(days.getFullYear(), days.getMonth(), days.getDate(), nowHours, nowMinutes,
                    nowSeconds)

                //console.log(nowDate, inputDate);
                let differenceInSeconds = Math.abs((nowDate - inputDate) / 1000);

                let minutes = Math.floor(differenceInSeconds / 60);
                let seconds = Math.floor(differenceInSeconds % 60);

                return `${pad(minutes, 2)}:${pad(seconds, 2)}`;
            } else {
                return '00:00';
            }
        }

        function data() {
            let totalPoint1 = 0;
            let totalPoint2 = 0;
            $.ajax({
                url: '/call-data/?tipe=tanding&arena=' + arena + '&partai=' + partai + '',
                method: 'GET',
                success: function (response) {
                    //console.log(response);


                    // $.ajax({
                    //     url: '/global-time', 
                    //     method: 'GET',
                    //     success: function(response2) {
                    //         console.log(response.time);

                    //         if (response.status != "pause") {
                    //             if (response.time != 0) {
                    //                 const timer = getselisih(response.time, response2);
                    //                 $('#timer1').text(timer);
                    //                 localStorage.setItem('waktu', timer);
                    //             } else {
                    //                 const timer = getselisih(null, response2);
                    //                 $('#timer1').text(timer);
                    //             }
                    //         } else {
                    //             localStorage.setItem('waktu-2', response2);
                    //             const savedTime = localStorage.getItem('waktu');
                    //             if(savedTime){
                    //                 $('#timer1').text(savedTime);
                    //             } else {
                    //                 $('#timer1').text("pause");
                    //             }
                    //         }
                    //     }
                    // });
                    //console.log(response);
                    if (response.binaan1 == 1) {
                        $('#binaan1').attr("src", "../assets/Assets/pointing_hand_red.png")
                        $('#binaan2').attr("src", "../assets/Assets/peace_hand.png")
                    } else if (response.binaan1 == 2) {
                        $('#binaan1').attr("src", "../assets/Assets/pointing_hand_red.png")
                        $('#binaan2').attr("src", "../assets/Assets/peace_hand_red.png")
                    } else if (response.binaan1 <= 0) {
                        $('#binaan1').attr("src", "../assets/Assets/pointing_hand.png")
                        $('#binaan2').attr("src", "../assets/Assets/peace_hand.png")
                    }

                    if (response.teguranTotal1 == 1) {
                        $('#teguran1').attr("src", "../assets/Assets/pointing_hand_red.png")
                        $('#teguran2').attr("src", "../assets/Assets/peace_hand.png")
                    } else if (response.teguranTotal1 == 2) {
                        $('#teguran1').attr("src", "../assets/Assets/pointing_hand_red.png")
                        $('#teguran2').attr("src", "../assets/Assets/peace_hand_red.png")
                    } else if (response.teguranTotal1 <= 0) {
                        $('#teguran1').attr("src", "../assets/Assets/pointing_hand.png")
                        $('#teguran2').attr("src", "../assets/Assets/peace_hand.png")
                    }

                    if (response.totalPeringatan1 == 0) {
                        $('#peringatan1').attr("src", "../assets/Assets/raising_hand.png")
                        $('#peringatan2').attr("src", "../assets/Assets/raising_hand.png")
                        $('#peringatan3').attr("src", "../assets/Assets/raising_hand.png")
                    } else if (response.totalPeringatan1 <= 1) {
                        $('#peringatan1').attr("src", "../assets/Assets/raising_hand_red.png")
                        $('#peringatan2').attr("src", "../assets/Assets/raising_hand.png")
                        $('#peringatan3').attr("src", "../assets/Assets/raising_hand.png")
                    } else if (response.totalPeringatan1 <= 2) {
                        $('#peringatan1').attr("src", "../assets/Assets/raising_hand_red.png")
                        $('#peringatan2').attr("src", "../assets/Assets/raising_hand_red.png")
                        $('#peringatan3').attr("src", "../assets/Assets/raising_hand.png")
                    } else if (response.totalPeringatan1 <= 3) {
                        $('#peringatan1').attr("src", "../assets/Assets/raising_hand_red.png")
                        $('#peringatan2').attr("src", "../assets/Assets/raising_hand_red.png")
                        $('#peringatan3').attr("src", "../assets/Assets/raising_hand_red.png")
                    }

                    if (response.binaan2 == 1) {
                        $('#mbinaan1').attr("src", "../assets/Assets/pointing_hand_red.png")
                        $('#mbinaan2').attr("src", "../assets/Assets/peace_hand.png")
                    } else if (response.binaan2 == 2) {
                        $('#mbinaan1').attr("src", "../assets/Assets/pointing_hand_red.png")
                        $('#mbinaan2').attr("src", "../assets/Assets/peace_hand_red.png")
                    } else if (response.binaan2 <= 0) {
                        $('#mbinaan1').attr("src", "../assets/Assets/pointing_hand.png")
                        $('#mbinaan2').attr("src", "../assets/Assets/peace_hand.png")
                    }


                    if (response.teguranTotal2 == 1) {
                        $('#mteguran1').attr("src", "../assets/Assets/pointing_hand_red.png")
                        $('#mteguran2').attr("src", "../assets/Assets/peace_hand.png")
                    } else if (response.teguranTotal2 == 2) {
                        $('#mteguran1').attr("src", "../assets/Assets/pointing_hand_red.png")
                        $('#mteguran2').attr("src", "../assets/Assets/peace_hand_red.png")
                    } else if (response.teguranTotal2 <= 0) {
                        $('#mteguran1').attr("src", "../assets/Assets/pointing_hand.png")
                        $('#mteguran2').attr("src", "../assets/Assets/peace_hand.png")
                    }

                    if (response.totalPeringatan2 == 0) {
                        $('#mperingatan1').attr("src", "../assets/Assets/raising_hand.png")
                        $('#mperingatan2').attr("src", "../assets/Assets/raising_hand.png")
                        $('#mperingatan3').attr("src", "../assets/Assets/raising_hand.png")
                    } else if (response.totalPeringatan2 <= 1) {
                        $('#mperingatan1').attr("src", "../assets/Assets/raising_hand_red.png")
                        $('#mperingatan2').attr("src", "../assets/Assets/raising_hand.png")
                        $('#mperingatan3').attr("src", "../assets/Assets/raising_hand.png")
                    } else if (response.totalPeringatan2 <= 2) {
                        $('#mperingatan1').attr("src", "../assets/Assets/raising_hand_red.png")
                        $('#mperingatan2').attr("src", "../assets/Assets/raising_hand_red.png")
                        $('#mperingatan3').attr("src", "../assets/Assets/raising_hand.png")
                    } else if (response.totalPeringatan2 <= 3) {
                        $('#mperingatan1').attr("src", "../assets/Assets/raising_hand_red.png")
                        $('#mperingatan2').attr("src", "../assets/Assets/raising_hand_red.png")
                        $('#mperingatan3').attr("src", "../assets/Assets/raising_hand_red.png")
                    }

                    //totalPoint1 += parseFloat(response.pukulanb) ?? 0;
                    //totalPoint1 += parseFloat(response.tendanganb) ?? 0;
                    totalPoint1 += parseFloat(response.jatuh1) ?? 0;
                    totalPoint1 -= parseFloat(response.totalBinaan1) ?? 0;
                    totalPoint1 -= parseFloat(response.teguranTotal1) ?? 0;
                    if (parseFloat(response.totalPeringatan1) / 1 == 1) {
                        totalPoint1 -= parseFloat(response.totalPeringatan1 * 5) ?? 0;
                    } else if (parseFloat(response.totalPeringatan1) / 2 == 1) {
                        totalPoint1 -= parseFloat(response.totalPeringatan1 * 5 * 3) ?? 0;
                    } else if (parseFloat(response.totalPeringatan1) / 3 == 1) {
                        totalPoint1 -= parseFloat(response.totalPeringatan1 * 5 * 6) ?? 0;
                    }

                    //totalPoint2 += parseFloat(response.pukulanm) ?? 0;
                    //totalPoint2 += parseFloat(response.tendanganm) ?? 0;
                    totalPoint2 += parseFloat(response.jatuh2) ?? 0;
                    totalPoint2 -= parseFloat(response.totalBinaan2) ?? 0;
                    totalPoint2 -= parseFloat(response.teguranTotal2) ?? 0;
                    if (parseFloat(response.totalPeringatan2) / 1 == 1) {
                        totalPoint2 -= parseFloat(response.totalPeringatan2 * 5) ?? 0;
                    } else if (parseFloat(response.totalPeringatan2) / 2 == 1) {
                        totalPoint2 -= parseFloat(response.totalPeringatan2 * 5 * 3) ?? 0;
                    } else if (parseFloat(response.totalPeringatan2) / 3 == 1) {
                        totalPoint2 -= parseFloat(response.totalPeringatan2 * 5 * 6) ?? 0;
                    }

                    //console.log(totalPoint1, totalPoint2, response.teguranTotal1, response.teguranTotal2);
                    //warna Score
                    if (response.score2 < response.score1) {
                        $('#scorebiru').removeClass("bg-blue-50 text-blue-500");
                        $('#scorebiru').addClass("bg-gradient-to-b from-blue-800 to-cyan-500 text-blue-50");
                        $("#scoremerah").removeClass(
                            "bg-gradient-to-b from-red-800 to-red-500 text-red-50");
                        $("#scoremerah").addClass("bg-red-50 text-red-500");
                    } else if (response.score2 > response.score1) {
                        $('#scorebiru').addClass("bg-blue-50 text-blue-500");
                        $('#scorebiru').removeClass(
                            "bg-gradient-to-b from-blue-800 to-cyan-500 text-blue-50");
                        $("#scoremerah").addClass("bg-gradient-to-b from-red-800 to-red-500 text-red-50");
                        $("#scoremerah").removeClass("bg-red-50 text-red-500");
                    } else if (response.score1 == response.score2) {
                        if (totalPoint1 > totalPoint2) {
                            $('#scorebiru').removeClass("bg-blue-50 text-blue-500");
                            $('#scorebiru').addClass(
                                "bg-gradient-to-b from-blue-800 to-cyan-500 text-blue-50");
                            $("#scoremerah").removeClass(
                                "bg-gradient-to-b from-red-800 to-red-500 text-red-50");
                            $("#scoremerah").addClass("bg-red-50 text-red-500");
                        } else if (totalPoint1 < totalPoint2) {
                            $('#scorebiru').addClass("bg-blue-50 text-blue-500");
                            $('#scorebiru').removeClass(
                                "bg-gradient-to-b from-blue-800 to-cyan-500 text-blue-50");
                            $("#scoremerah").addClass(
                                "bg-gradient-to-b from-red-800 to-red-500 text-red-50");
                            $("#scoremerah").removeClass("bg-red-50 text-red-500");
                        } else {
                            $('#scorebiru').removeClass("bg-blue-50 text-blue-500");
                            $("#scoremerah").removeClass("bg-red-50 text-red-500");
                            $('#scorebiru').addClass(
                                "bg-gradient-to-b from-blue-800 to-cyan-500 text-blue-50");
                            $("#scoremerah").addClass(
                                "bg-gradient-to-b from-red-800 to-red-500 text-red-50");
                        }
                    } else {
                        $('#scorebiru').addClass("bg-blue-50 text-blue-500");
                        $('#scorebiru').removeClass(
                            "bg-gradient-to-b from-blue-800 to-cyan-500 text-blue-50");
                        $("#scoremerah").removeClass("bg-red-500 text-red-50");
                        $("#scoremerah").addClass("bg-red-50 text-red-500");
                    }

                    if (response.statusPertandingan == "finish") {
                        window.location.href = `redirect?arena=${arena}&partai=${partai}&role=rekapTanding`;
                    }

                    //// console.log(timer);
                    $('#partai').text(`Partai ${response.partai}`);
                    $('#infoKelas').text(response.infoKelas);
                    $('#namaBiru').text(response.namaBiru);
                    $('#namaMerah').text(response.namaMerah);
                    $('#kontigenBiru').text(response.kontigenBiru);
                    $('#kontigenMerah').text(response.kontigenMerah);
                    $('#jatuh2').text('' + ' ' + response.jatuh1);
                    //$('#jatuh2').text('' + ' ' + response.totalJatuhan2);
                    $('#jatuh1').text('' + ' ' + response.jatuh2);
                    //$('#jatuh1').text('' + ' ' + response.totalJatuhan1);
                    $('#bina2').text('x' + ' ' + response.binaan1);
                    $('#bina1').text('x' + ' ' + response.binaan2);
                    $('#teguran2').text('x' + ' ' + response.teguran1);
                    $('#teguran1').text('x' + ' ' + response.teguran2);
                    $('#peringatan2').text('x' + ' ' + response.peringatan1);
                    $('#peringatan1').text('x' + ' ' + response.peringatan2);
                    $('#score1').text(response.score2);
                    $('#score2').text(response.score1);
                    $('#score2').attr('name', response.idBiru);
                    $('#score1').attr('name', response.idMerah);
                    // for (let index = 1; index < 4; index++) {
                    //     var name = `jurip${index}biru`;
                    //     var action = document.getElementById(`juri${index}bp`);
                    //     if (response[name] > 0) {

                    //         action.classList.add('bb2');
                    //     } else {
                    //         action.classList.remove('bb2');
                    //     }

                    // }
                    // for (let index = 1; index < 4; index++) {
                    //     var name = `jurit${index}biru`;
                    //     var action = document.getElementById(`juri${index}bt`);
                    //     if (response[name] > 0) {

                    //         action.classList.add('bb2');
                    //     } else {
                    //         action.classList.remove('bb2');
                    //     }

                    // }
                    // for (let index = 1; index < 4; index++) {
                    //     var name = `jurip${index}merah`;
                    //     var action = document.getElementById(`juri${index}mp`);
                    //     if (response[name] > 0) {

                    //         action.classList.add('br2');
                    //     } else {
                    //         action.classList.remove('br2');
                    //     }

                    // }
                    // for (let index = 1; index < 4; index++) {
                    //     var name = `jurit${index}merah`;
                    //     var action = document.getElementById(`juri${index}mt`);
                    //     if (response[name] > 0) {

                    //         action.classList.add('br2');
                    //     } else {
                    //         action.classList.remove('br2');
                    //     }

                    // }

                    if (response.notif != "not") {
                        var name = "modal" + response.notif;
                        let myModal = new bootstrap.Modal(document.getElementById(name));
                        lastmodal = name;
                        let juri1 = document.getElementById('juri1-' + response.notif);
                        let juri2 = document.getElementById('juri2-' + response.notif);
                        let juri3 = document.getElementById('juri3-' + response.notif);

                        $.ajax({
                            url: '/call-data/?tipe=notif&arena=' + arena + '&status=' + response
                                .notif,
                            method: 'GET',
                            success: function (response) {
                                let master = response.data
                                master.forEach(data => {
                                    if (juri1) {
                                        if (data.id_juri == "Juri_1") {
                                            if (data.score == "biru") {
                                                juri1.classList.remove('br', 'by',
                                                    'bn');
                                                juri1.classList.add('bb');
                                            } else if (data.score == "merah") {
                                                juri1.classList.remove('bb', 'by',
                                                    'bn');
                                                juri1.classList.add('br');
                                            } else if (data.score == "invalid") {
                                                juri1.classList.remove('bb', 'br',
                                                    'bn');
                                                juri1.classList.add('by');
                                            } else {
                                                juri3.classList.remove('bb', 'br',
                                                    'by');
                                                juri3.classList.add('bn');
                                            }
                                        }

                                    }
                                    if (juri2) {
                                        if (data.id_juri == "Juri_2") {
                                            if (data.score == "biru") {
                                                juri2.classList.remove('br', 'by',
                                                    'bn');
                                                juri2.classList.add('bb');
                                            } else if (data.score == "merah") {
                                                juri2.classList.remove('bb', 'by',
                                                    'bn');
                                                juri2.classList.add('br');
                                            } else if (data.score == "invalid") {
                                                juri2.classList.remove('bb', 'br',
                                                    'bn');
                                                juri2.classList.add('by');
                                            } else {
                                                juri3.classList.remove('bb', 'br',
                                                    'by');
                                                juri3.classList.add('bn');
                                            }
                                        }
                                    }
                                    if (juri3) {
                                        if (data.id_juri == "Juri_3") {
                                            if (data.score == "biru") {
                                                juri3.classList.remove('br', 'by',
                                                    'bn');
                                                juri3.classList.add('bb');
                                            } else if (data.score == "merah") {
                                                juri3.classList.remove('bb', 'by',
                                                    'bn');
                                                juri3.classList.add('br');
                                            } else if (data.score == "invalid") {
                                                juri3.classList.remove('bb', 'br',
                                                    'bn');
                                                juri3.classList.add('by');
                                            } else {
                                                juri3.classList.remove('bb', 'br',
                                                    'by');
                                                juri3.classList.add('bn');
                                            }
                                        }

                                    }
                                    //console.log(response);
                                });

                            }
                        });
                        // launchModal(myModal);
                    } else if (response.notif == "not") {
                        // CloseModal(lastmodal);
                    }

                }
            });
        }



        function changebabak() {
            var elemenDiv = document.getElementById("arenaid");
            var id = elemenDiv.getAttribute("name");

            $.ajax({
                url: '/call-data/?tipe=checkbabak&id=' + id + '',
                method: 'GET',
                success: function (response) {
                    // console.log(response.data);
                    var idbabak = response.data;
                    if (idbabak == 1) {
                        babak1();
                    } else if (idbabak == 2) {
                        babak2();
                    } else if (idbabak == 3) {
                        babak3();
                    }
                }
            });
        }

        function updatescore1() {
            var elemenDiv = document.getElementById("score1"); // Mendapatkan elemen dengan ID "bina2"
            var id = elemenDiv.getAttribute("name"); // Mengambil nilai ID elemen
            var arena_id = document.getElementById('arenaid').getAttribute('name');

            $.ajax({
                url: '/call-data/?tipe=check&id=' + id + '&kt=check&arena=' + arena_id + '',
                method: 'GET',
                success: function (response) {
                    // console.log(response.data);
                    // Panggil kembali fungsi untuk polling berikutnya
                }
            });
        }


        function updatescore2() {
            var elemenDiv = document.getElementById("score2"); // Mendapatkan elemen dengan ID "bina2"
            var id = elemenDiv.getAttribute("name"); // Mengambil nilai ID elemen
            var arena_id = document.getElementById('arenaid').getAttribute('name');


            $.ajax({
                url: '/call-data/?tipe=check&id=' + id + '&kt=check&arena=' + arena_id + '',
                method: 'GET',
                success: function (response) {
                    // console.log(response.data);
                    // Panggil kembali fungsi untuk polling berikutnya
                }
            });
        }

        function currentTimer() {
            $.ajax({
                url: '/timeradmin/?tipe=getCurrent&arena=' + arena + '',
                method: 'GET',
                success: function (response) {
                    // console.log(response.data);
                }
            });
        }
        data();
        // updatescore1();
        // updatescore2();
        changebabak();

    }

    websocket();
    calldata();
    //  setInterval(calldata, 3000);
</script>
@include('addon.tanding.reload');