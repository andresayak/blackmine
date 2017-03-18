$(document).ready(function () {
    $('[data-time]').each(fixTime);
});

function fixTime() {
    var el = this;
    var time = $(el).data('time');
    var date = new Date(time * 1000);

    var midnight = new Date();
    midnight.setHours(0, 0, 0, 0);

    var hour = date.getHours();
    hour = ((hour < 10) ? '0' : '') + hour;
    var min = date.getMinutes();
    min = ((min < 10) ? '0' : '') + min;
    var year = date.getUTCFullYear();
    var month = date.getUTCMonth() + 1;
    month = ((month < 10) ? '0' : '') + month;
    var day = date.getUTCDate();
    day = ((day < 10) ? '0' : '') + day;

    var format = function (h, m, d, M, y) {
        if (date.getTime() >= midnight.getTime()) {
            return 'today in' + ' ' + h + ':' + m;
        } else if (date.getTime() >= midnight.getTime() - (3600 * 24 * 1000)) {
            return 'time.yesterday in' + ' ' + h + ':' + m;
        } else {
            return h + ':' + m + ' ' + d + '-' + M + '-' + y;
        }
    };
    $(el).text(format(hour, min, day, month, year));
}

