$(document).ready(function () {
    $('.message a').click(function () {
        $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
    });
    
    $("#tablica").DataTable();

    var simb1 = "!";
    var simb2 = "?";
    var simb3 = "#";
    $('#ime').keyup(function () {
        var ime = $("#ime").val();
        var zastavica = 0;
        if (ime.indexOf(simb1) != -1) {
            zastavica = 1;
        }
        if (ime.indexOf(simb2) != -1) {
            zastavica = 1;
        }
        if (ime.indexOf(simb3) != -1) {
            zastavica = 1;
        }
        if (zastavica === 0) {
            $("#submit").removeClass("onemoguceno");
        } else {
            $("#submit").addClass("onemoguceno");
        }
    });

    $('#prezime').keyup(function () {
        var prezime = $("#prezime").val();
        var zastavica = 0;
        if (prezime.indexOf(simb1) != -1) {
            zastavica = 1;
        }
        if (prezime.indexOf(simb2) != -1) {
            zastavica = 1;
        }
        if (prezime.indexOf(simb3) != -1) {
            zastavica = 1;
        }
        if (zastavica === 0) {
            $("#submit").removeClass("onemoguceno");
        } else {
            $("#submit").addClass("onemoguceno");
        }
    });



    $('#korime').keyup(function () {
        var zastavica = 0;
        var response = '';
        $.ajax({
            type: "GET",
            url: "korisnici.php",
            async: false,
            success: function (text) {
                response = text;
            }
        });
        console.log(response);
        var korime = $("#korime").val();
        console.log(response.indexOf(korime));
        if (response.indexOf(korime) != -1) {
            zastavica = 1;
        } else {
            zastavica = 0;
        }
        if (zastavica === 1) {
            $("#submit").addClass("onemoguceno");
        } else {
            $("#submit").removeClass("onemoguceno");
        }
    });

    $('#email').keyup(function () {
        var zastavica = 0;
        var email = $("#email").val();
        var reg = new RegExp(/^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/);
        if (reg.test(email)) {
            zastavica = 0;
        } else {
            zastavica = 1;
        }
        if(zastavica === 1) {
            $("#submit").addClass("onemoguceno");
        }
        else {
            $("#submit").removeClass("onemoguceno");
        }
    });

    $('#lozinka2').keyup(function () {
        var zastavica = 0;
        var lozinka1 = $("#lozinka1").val();
        var lozinka2 = $("#lozinka2").val();
        if (lozinka2 !== lozinka1) {
            zastavica = 1;
        } else {
            zastavica = 0;
        }
        if(zastavica === 1) {
            $("#submit").addClass("onemoguceno");
        }
        else {
            $("#submit").removeClass("onemoguceno");
        }
    });
});

