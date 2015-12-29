[].map.call(document.querySelectorAll('.profile'), function(el) {
    el.classList.toggle('profile--open');
});
$(document).ready(function () {
    notif({
        type: "info",
        msg: "Welcome to Orbitcoin web-wallet",
        autohide: true,
        position: "bottom",
        opacity: 0.7,
        timeout: 5000,
        zindex: 0,
        offset: 0,
        fade: 100,
        bgcolor: "black"
    });


    $(".btn").on("click", function () {
        notif({
            type: "alert",
            msg: "Processing...",
            autohide: false,
            position: "bottom",
            opacity: 0.7,
            zindex: 0,
            bgcolor: "yellow",
            offset: 0,
            fade: 100,
        });


        var username = $("#fieldUser").val();
        var password = $("#fieldPassword").val();

        $.ajax({
            type: "POST",
            url: "php/wallet_login.php",
            data: {Username: username, Password: password},
            success: function(data) {
                if(data != "Please Confirm Your Email!" && data != "Incorrect Password!" && data != "Username Not Found!" && data != '') {
                    notif({
                        type: "success",
                        msg: data,
                        autohide: false,
                        position: "bottom",
                        opacity: 0.7,
                        zindex: 0,
                        bgcolor: "#478B16",
                        offset: 0,
                        fade: 100,
                    });

                    [].map.call(document.querySelectorAll('.profile'), function(el) {
                        el.classList.toggle('profile--open');
                    });


                    setTimeout(function () {
                        window.location = "wallet.php";
                    }, 3000)

                } else {

                    notif({
                        type: "fail",
                        msg: data,
                        autohide: false,
                        position: "bottom",
                        color: "white",
                        opacity: 0.7,
                        zindex: 0,
                        bgcolor: "red",
                        offset: 0,
                        fade: 100,
                    });

                }
            }

        });

    });
});