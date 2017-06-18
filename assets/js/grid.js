$(window).load(function() {
    // When the page has loaded
    $("#content").fadeIn(1000);
});

$(document).ready(function() {
    var x = document.images;
    var txt = "";
    var i;
    for (i = 0; i < x.length; i++) {
        $(x[i]).fadeIn(1000);
    }
});








$(document).on('click', '.loadmore', function () {
    $(this).text('Loading...');
    // var ele = $(this).parent('li');
    $.ajax({
        url: 'loadmore.php',
        type: 'POST',
        data: {
            page: $(this).data('page'),
        },
        success: function (response) {
            if (response) {
                var ele = document.getElementById("button");
                ele.innerHTML = "";
                if ((response.search("<li"))){
                    var p = response.search("<li");
                    var s = response.substr(p);
                    response = response.replace(s,"");
                    $("#button").append(s);
                    console.log("Button: " + s);
                }
                $("#photos").append(response);
                window.scrollTo(0,document.body.scrollHeight);
                console.log("Data: " + response);

                var $target = $('html,body');
                setTimeout($target.animate({scrollTop: $target.height()}, 1000), 500);

                $(document).ready(function() {
                    var x = document.images;
                    var txt = "";
                    var i;
                    for (i = 0; i < x.length; i++) {
                        $(x[i]).fadeIn(1000);
                    }

                });

            }
        }
    });
});